<?php

namespace dbproxy;

namespace dbproxy;

abstract class MysqlProxyBase {

    private $tableName; // the name of the table
    private $fieldList;
    /* The list of the fields in the correspondent SQL table
     * The first must be the IDENTITY field.
     */
    private $conn;

    public function __construct($connection, $tableName, $fieldList) {
        $this->conn = $connection;
        $this->tableName = $tableName;
        $this->fieldList = $fieldList;
    }

    private function _fetchAllAssoc(&$result_set) {
        $r = null;
        if ($result_set) {
            $r = [];
            while ($row = $result_set->fetch_assoc()) {
                array_push($r, $row);
            }
            $result_set->free();
        }
        return $r;
    }

    private function _getFieldListString() {
        $r = null;
        $n = count($this->fieldList);
        for ($i = 0; $i < $n-1; $i++) {
            $r .= $this->fieldList[$i] . ', ';
        }
        $r .= $this->fieldList[$n - 1];
        return $r;
    }

    private function _sqlFormat($field) {
        $r = 'NULL';
        if (isset($field)) {
            if (is_string($field)) {
                $r = "'$field'";
            } else if (is_bool($field)) {
                $r = ($field) ? '1' : '0';
            } else {
                $r = (string) $field;
                // TODO possibile convertire facilmente data in stringa? e data e ora?
            }
        }
        return $r;
    }

    private function _where_helper($clauses, $andor) {
        $r = null;
        $first = true;
        foreach ($clauses as $field => $value) {
            if ($first) {
                $first = false;
                $r = '(';
            } else {
                $r .= " $andor ";
            }

            if ($field === 'or' || $field === 'OR') {
                $r .= $this->_where_helper($value, 'OR');
            } else {
                $op = '=';
                if (is_array($value)) {
                    // necessariamente un operatore diverso dall'uguale
                    if (count(array_keys($value)) === 1) {
                        $opDefinition = array_keys($value)[0];
                        if ($opDefinition) {
                            switch ($opDefinition) {
                                case 'lt':
                                    $op = '<';
                                    break;
                                case 'le':
                                    $op = '<=';
                                    break;
                                case 'gt':
                                    $op = '>';
                                    break;
                                case 'ge':
                                    $op = '>=';
                                    break;
                                case 'eq':
                                    $op = '=';
                                    break;
                                case 'ne':
                                    $op = '!=';
                                    break;
                                case 'like':
                                    $op = 'LIKE';
                                    break;
                                default:
                                    throw new MysqlProxyBaseException('Unexptected operator definition (' . $opDefinition . ')', 1);
                            }
                            $value = $value[$opDefinition];
                        } else {
                            throw new MysqlProxyBaseException('Malformed clause ' . var_export($value, true), 2);
                        }
                    } else {
                        throw new MysqlProxyBaseException('Malformed clause ' . var_export($value, true), 2);
                    }
                }
                $field = mysql_real_escape_string($field);
                if (is_string($value)) {
                    $value = mysql_real_escape_string($value);
                }
                $r .= "`$field` $op " . $this->_sqlFormat($value);
            }
        }
        $r .= ')';
        return $r;
    }

    private function _where($clauses) {
        return ' WHERE ' . $this->_where_helper($clauses, 'AND');
    }

    abstract protected function _castData(&$data);

    public function get($id) {
        $r = null;
        $query = 'SELECT '
                . $this->_getFieldListString()
                . ' FROM `' . $this->tableName . '` '
                . ' WHERE ' . $this->fieldList[0] . ' = ' . $this->_sqlFormat($id);
        $rs = $this->conn->query($query);
        if ($this->conn->errno) {
            throw new \Exception($this->conn->errno . ' ' . $this->conn->error);
        }
        $r = $rs->fetch_assoc();
        $this->_castData($r);
        return $r;
    }

    /**
     * Gets the instances based on the valuse provided by the pars.
     * The values are in in AND.
     * 
     * @param associative array  $pars
     * @param int $limit the number of returnes rows
     * @return array of associative arrays
     * @throws \Exception
     */
    public function getSelected($pars, $limit = 50) {
        $r = null;
        $query = 'SELECT '
                . $this->_getFieldListString()
                . ' FROM `' . $this->tableName . '` '
                . $this->_where($pars)
                . " LIMIT $limit";
        $rs = $this->conn->query($query);
        if ($this->conn->errno) {
            throw new \Exception($this->conn->errno . ' ' . $this->conn->error);
        }
        $r = $this->_fetchAllAssoc($rs);

        if ($r) {
            foreach ($r as &$temp) {
                $this->_castData($temp);
            }
        }
        return $r;
    }

    public function getAll($limit = 50) {
        $r = null;
        $query = 'SELECT '
                . $this->_getFieldListString()
                . ' FROM `' . $this->tableName . '` '
                . " LIMIT $limit";
        $rs = $this->conn->query($query);
        if ($this->conn->errno) {
            throw new \Exception($this->conn->errno . ' ' . $this->conn->error);
        }
        $r = $this->_fetchAllAssoc($rs);

        if ($r) {
            foreach ($r as &$temp) {
                $this->_castData($temp);
            }
        }
        return $r;
    }

    private function _createFieldListAndValues($data) {
        $first = true;
        foreach ($data as $key => $value) {
            $key = $this->conn->escape_string($key);
            if ($first) {
                $first = false;
                $r = "( `$key`";
            } else {
                $r .= ", `$key`";
            }
        }
        $r .= ') ';
        $first = true;
        foreach ($data as $key => $value) {
            $value = $this->conn->escape_string($value);
            if ($first) {
                $first = false;
                $r .= 'VALUES ( ' . $this->_sqlFormat($value);
            } else {
                $r .= ', ' . $this->_sqlFormat($value);
            }
        }
        $r .= ') ';
        return $r;
    }

    /**
     * Add a new record to the table.
     * The field of $data correspondent to the AUTO_INCREMENT field can be either set to null or not set.
     * 
     * @param associative array $data
     * @return the auto generated id
     */
    public function add($data) {
        $query = 'INSERT INTO `' . $this->tableName . '` '
                . $this->_createFieldListAndValues($data);
        $this->conn->query($query);
        if ($this->conn->errno) {
            throw new Exception($this->conn->errno . ' ' . $this->conn->error);
        }
        $r = $this->conn->insert_id;
        return $r;
    }

    /**
     * 
     * @param associative array $data. The first element must be the unique identifier.
     * @return boolean
     * @throws Exception
     */
    public function update($data) {
        $r = false;
        $identifier = array_shift($data);
        $identifierFieldName = $this->conn->escape_string(array_keys($identifier)[0]);
        $identifierFieldValue = $this->conn->escape_string($identifier[$identifierFieldName]);
        $query = 'UPDATE `' . $this->tableName . '` '
                . $this->_createFieldListAndValues($data)
                . ' WHERE `' . $identifierFieldName . '` = ' . $this->_sqlFormat($identifierFieldValue);
        $this->conn->query($query);
        if ($this->conn->errno) {
            throw new Exception($this->conn->errno . ' ' . $this->conn->error);
        }

        $n = $this->conn->affected_rows();
        switch ($n) {
            case 0:
                break;
            case 1:
                $r = true;
                break;
            default:
                throw new Exception("Unexpected number of affected rows ($n)");
        }

        return $r;
    }

    public function delete($id) {
        $r = false;
        $query = 'DELETE FROM `' . $this->tableName . '` '
                . ' WHERE ' . $this->fieldList[0] . ' = ' . $this->_sqlFormat($id);
        $this->conn->query($query);
        if ($this->conn->errno) {
            throw new Exception($this->conn->errno . ' ' . $this->conn->error);
        }
        $n = $this->conn->affected_rows();
        switch ($n) {
            case 0:
                break;
            case 1:
                $r = true;
                break;
            default:
                throw new Exception("Unexpected number of affected rows ($n)");
        }
        return $r;
    }

}
