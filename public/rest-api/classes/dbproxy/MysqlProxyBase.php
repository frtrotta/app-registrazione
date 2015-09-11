<?php

namespace dbproxy;

namespace dbproxy;

abstract class MysqlProxyBase {

    private $tableName; // the name of the table
    private $fieldList;
    /* The list of the fields in the correspondent SQL table
     * The first must be the IDENTITY field.
     */
    public $conn;

    public function __construct(&$connection, $tableName, $fieldList) {
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
        for ($i = 0; $i < $n - 1; $i++) {
            $r .= $this->fieldList[$i] . ', ';
        }
        $r .= $this->fieldList[$n - 1];
        return $r;
    }

    private function _sqlFormat($field) {
        $r = 'NULL';
        if (isset($field)) {
            if (is_string($field)) {
                $field = $this->conn->escape_string($field);
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
                $field = $this->conn->escape_string($field);
                $r .= "`$field` $op " . $this->_sqlFormat($value);
            }
        }
        $r .= ')';
        return $r;
    }

    private function _where($selectionClause) {
        $r = null;
        if (isset($selectionClause)) {
            unset($selectionClause['limit']);
            unset($selectionClause['skip']);
            unset($selectionClause['sort']);
            if (count($selectionClause) > 0) {
                $r = ' WHERE ' . $this->_where_helper($selectionClause, 'AND');
            }
        }
        return $r;
    }

    private function _sort_helper($sort) {
        $first = true;
        foreach ($sort as $field => $v) {
            $field = $this->conn->escape_string($field);
            if ($first) {
                $first = false;
                $r = (" ORDER BY `$field` " . (($v > 0) ? 'ASC' : 'DESC'));
            } else {
                $r .= (", `$field` " . (($v > 0) ? 'ASC' : 'DESC'));
            }
        }
        return $r;
    }

    private function _sort($selectionClause) {
        $r = null;
        if (isset($selectionClause['sort'])) {
            $r = $this->_sort_helper($selectionClause['sort']);
        }
        return $r;
    }

    private function _limit_helper($pars) {
        $r = ' LIMIT ';
        if (!is_integer($pars['limit'])) {
            throw new MysqlProxyBaseException('limit must be integer', 10);
        }
        if (isset($pars['skip'])) {
            if (!is_integer($pars['skip'])) {
                throw new MysqlProxyBaseException('skip must be integer', 11);
            }
            $r .= $pars['skip'] . ',';
        }
        $r .= $pars['limit'];
        return $r;
    }

    private function _limit($selectionClause) {
        $r = ' LIMIT 50';
        if (isset($selectionClause['limit'])) {
            $r = $this->_limit_helper($selectionClause);
        }
        return $r;
    }

    abstract protected function _castData(&$data);

    /**
     * Completed the data with the possibile related data
     */
    abstract protected function _complete(&$data);

    /**
     * 
     * @throws Exception
     */
    protected function _getOptionalChildIds($idField, $idValue, $childIdField, $tableName) {
        $query = "SELECT $childIdField"
                . " FROM $tableName"
                . " WHERE $idField = $idValue";
        $rs = $this->conn->query($query);
        if ($this->conn->errno) {
            throw new Exception($this->conn->error, $this->conn->errno);
        }
        return $this->fetch_all();
    }

    abstract protected function _isCoherent($data);

    protected function _unsetField(&$set, $fieldName) {
        foreach ($set as &$temp) {
            unset($temp[$fieldName]);
        }
    }

    public function get($id, $complete = false) {
        $r = null;
        $query = 'SELECT '
                . $this->_getFieldListString()
                . ' FROM `' . $this->tableName . '` '
                . ' WHERE ' . $this->fieldList[0] . ' = ' . $this->_sqlFormat($id);
        $rs = $this->conn->query($query);
        if ($this->conn->errno) {
            throw new \Exception($this->conn->error, $this->conn->errno);
        }
        $r = $rs->fetch_assoc();
        if ($r) {
            $this->_castData($r);
            if ($complete) {
                $this->_complete($r);
            }
        }
        return $r;
    }

    /**
     * Gets the instances based on the valuse provided by the pars.
     * The values are in in AND.
     * 
     * @param associative array  $selectionClause
     * @param int $limit the number of returnes rows
     * @return array of associative arrays
     * @throws \Exception
     */
    public function getSelected($selectionClause, $complete = false, $limit = 50) {
        $r = null;
        if (is_array($selectionClause)) {
            $query = 'SELECT '
                    . $this->_getFieldListString()
                    . ' FROM `' . $this->tableName . '` '
                    . $this->_where($selectionClause)
                    . $this->_sort($selectionClause)
                    . $this->_limit($selectionClause);
            $rs = $this->conn->query($query);
            if ($this->conn->errno) {
                if($this->conn->errno === 1054) {
                    // One ore more fields have not the correct name
                    throw new MysqlProxyBaseException($this->conn->error, $this->conn->errno);
                }
                throw new \Exception($this->conn->error, $this->conn->errno);
            }
            $r = $this->_fetchAllAssoc($rs);
        }

        if ($r) {
            foreach ($r as &$temp) {
                $this->_castData($temp);
                if ($complete) {
                    $this->_complete($temp);
                }
            }
        }
        return $r;
    }

    public function getAll($complete = false, $limit = 50) {
        $r = null;
        $query = 'SELECT '
                . $this->_getFieldListString()
                . ' FROM `' . $this->tableName . '` '
                . " LIMIT $limit";
        $rs = $this->conn->query($query);
        if ($this->conn->errno) {
            throw new \Exception($this->conn->error, $this->conn->errno);
        }
        $r = $this->_fetchAllAssoc($rs);

        if ($r) {
            foreach ($r as &$temp) {
                $this->_castData($temp);
                if ($complete) {
                    $this->_complete($temp);
                }
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
                $fieldList = "( `$key`";
                $valueList = ' VALUES ( ' . $this->_sqlFormat($value);
            } else {
                $fieldList .= ", `$key`";
                $valueList .= ', ' . $this->_sqlFormat($value);
            }
        }
        $fieldList .= ') ';
        $valueList .= ') ';
        return $fieldList . $valueList;
    }

    protected function _is_date($value) {
        $d = DateTime::createFormat('Y/m/d', $value);
        return $d && $d->format('Y/m/d') === $value;
    }
    
    protected function _is_datetime($value) {
        $d = DateTime::createFormat('Y/m/d H:i:s', $value);
        return $d && $d->format('Y/m/d H:i:s') === $value;
    }
    
    protected function _is_date_optional($value) {
        if(isset($value)) {
            return $this->_is_date($value);
        }
        else {
            return true;
        }
    }
    
    protected function _is_datetime_optional($value) {
        if(isset($value)) {
            return $this->_is_datetime($value);
        }
        else {
            return true;
        }
    }
    
    protected function _is_integer_optional($value) {
        if(isset($value)) {
            return $this->is_integer($value);
        }
        else {
            return true;
        }
    }
    
    protected function _is_bool_optional($value) {
        if(isset($value)) {
            return $this->is_bool($value);
        }
        else {
            return true;
        }
    }
    
    protected function _is_float_optional($value) {
        if(isset($value)) {
            return $this->is_float($value);
        }
        else {
            return true;
        }
    }

    /**
     * Add a new record to the table.
     * The field of $data correspondent to the AUTO_INCREMENT field can be either set to null or not set.
     * 
     * @param associative array $data
     * @return $data with the generated identifier
     */
    public function add($data) {
        if ($this->_isCoherent($data)) {
            $query = 'INSERT INTO `' . $this->tableName . '` '
                    . $this->_createFieldListAndValues($data);
            $this->conn->query($query);
            if ($this->conn->errno) {
                throw new Exception($this->conn->error, $this->conn->errno);
            }
            $r = $data;
            $r[$this->fieldList[0]] = $this->conn->insert_id;
        } else {
            $e = var_export($data, true);
            throw new MysqlProxyBaseException("Incoherent data $e", 20);
        }
        return $r;
    }

    /**
     * 
     * @param associative array $data. The first element must be the unique identifier.
     * @return null or the updated (passed) data
     * @throws Exception
     */
    public function update($data) {
        $r = null;
        if ($this->_isCoherent($data)) {
            $identifier = array_shift($data);
            $identifierFieldName = $this->conn->escape_string(array_keys($identifier)[0]);
            $identifierFieldValue = $this->conn->escape_string($identifier[$identifierFieldName]);
            $query = 'UPDATE `' . $this->tableName . '` '
                    . $this->_createFieldListAndValues($data)
                    . ' WHERE `' . $identifierFieldName . '` = ' . $this->_sqlFormat($identifierFieldValue);
            $this->conn->query($query);
            if ($this->conn->errno) {
                throw new Exception($this->conn->error, $this->conn->errno);
            }

            $n = $this->conn->affected_rows();
            switch ($n) {
                case 0:
                    break;
                case 1:
                    $r = $data;
                    break;
                default:
                    throw new Exception("Unexpected number of affected rows ($n)");
            }
        } else {
            $e = var_export($data, true);
            throw new MysqlProxyBaseException("Incoherent data $e", 21);
        }

        return $r;
    }

    public function delete($id) {
        $r = false;
        $query = 'DELETE FROM `' . $this->tableName . '` '
                . ' WHERE ' . $this->fieldList[0] . ' = ' . $this->_sqlFormat($id);
        $this->conn->query($query);
        if ($this->conn->errno) {
            throw new Exception($this->conn->error, $this->conn->errno);
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
