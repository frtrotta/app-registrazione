<?php

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
        $this->$fieldList = $fieldList;
    }

    private function _fetchAllAssoc($result_set) {
        $r = NULL;
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
        $n = count($this->fieldList) - 1;
        for ($i = 0; $i < $n; $i++) {
            $r .= $this->fieldList[$i] . ', ';
        }
        $r .= $this->fieldList[$n - 1];
        return $r;
    }

//    private function _getSqlValueList($data) {
//        $r = null;
//        $n = count($this->fieldList) - 1;
//        for ($i = 0; $i < $n; $i++) {
//            $r .= _sqlFormat($data[$this->fieldList[$i]]) . ', ';
//        }
//        $r .= _sqlFormat($data[$this->fieldList[$n - 1]]);
//        return $r;
//    }

    private function _sqlFormat($field) {
        $r = 'NULL';
        if (isset($field)) {
            if (is_string($field)) {
                $r = "'$field'";
            } else if (is_boolean($field)) {
                $r = ($field) ? '1' : '0';
            } else {
                $r = (string) $field;
                // TODO possibile convertire facilmente data in stringa? e data e ora?
            }
        }
        return $r;
    }

    abstract protected function _castData(&$data);

    public function get($id) {
        $r = null;
        $query = 'SELECT '
                . $this->_getFieldListString()
                . ' FROM `' . $this->tableName . '` '
                . ' WHERE ' . $this->fields[0] . ' = ' . $this->_sqlFormat($id);
        $rs = $this->conn->query($query);
        if ($rs) {
            $r = $rs->fetch_assoc();
            $this->_castData($r);
        }
        return $r;
    }

    /**
     * Gets the instances based on the valuse provided by the pars.
     * The values are in in AND.
     * 
     * @param associative array $pars
     * @return associative array withe the selected row or null
     * @throws Exception
     */
    public function getSelected($pars) {
        $r = null;
        $query = 'SELECT '
                . $this->_getFieldListString()
                . ' FROM `' . $this->tableName . '` ';
        if (isset($pars)) {
            $first = true;
            foreach ($pars as $key => $value) {
                if ($first) {
                    $first = false;
                    $where = " WHERE $key = " . $this->_sqlFormat($value);
                } else {
                    $where .= " AND $key = " . $this->_sqlFormat($value);
                }
            }
        }
        $rs = $this->conn->query($query);
        if ($this->conn->errno) {
            throw new Exception($this->conn->errno . ' ' . $this->conn->error);
        }
        $r = $rs->_fetchAllAssoc();
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
        $r = null;
        $query = 'INSERT INTO `' . $this->tableName . '` '
                . $this->_createFieldListAndValues($data);
        $this->conn->query($query);
        if ($this->conn->errno) {
            throw new Exception($this->conn->errno . ' ' . $this->conn->error);
        }
        $r = $conn->insert_id;
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
        $query = 'DELETE FROM ' . $this->tableName
                . ' WHERE ' . $this->fields[0] . ' = ' . $this->_sqlFormat($id);
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
