<?php

abstract class MysqlProxyBase {

    protected $tableName; // the name of the table
    protected $fieldList; // the list of the fields in the correspondent SQL table
    protected $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    protected function _fetchAllAssoc($result_set) {
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

    protected function _getFieldListString() {
        $r = null;
        $n = count($this->fieldList) - 1;
        for ($i = 0; $i < $n; $i++) {
            $r .= $this->fieldList[$i] . ', ';
        }
        $r .= $this->fieldList[$n - 1];
        return $r;
    }

    protected function _getSqlValueList($data) {
        $r = null;
        $n = count($this->fieldList) - 1;
        for ($i = 0; $i < $n; $i++) {
            $r .= _sqlFormat($data[$this->fieldList[$i]]) . ', ';
        }
        $r .= _sqlFormat($data[$this->fieldList[$n - 1]]);
        return $r;
    }

    protected function _sqlFormat($field) {
        $r = 'NULL';
        if (isset($field)) {
            if (is_string($field)) {
                $r = "'$field'";
            } else if (is_boolean($field)) {
                $r = ($field) ? '1' : '0';
            } else {
                $r = (string) $field;
            }
        }
        return $r;
    }

    abstract protected function _castData(&$data);

    public function get($id) {
        $r = null;
        $query = 'SELECT '
                . $this->_getFieldListString()
                . ' FROM ' . $this->tableName
                . " WHERE $id = '$id'";
        $rs = $this->conn->query($query);
        if ($rs) {
            $r = $rs->fetch_assoc();
            $this->_castData($r);
        }
        return $r;
    }

    public function getAll() {
        $r = null;
        $query = 'SELECT '
                . $this->_getFieldListString()
                . ' FROM ' . $this->tableName
                . " WHERE $id = '$id'";
        $rs = $this->conn->query($query);
        if ($rs) {
            $r = $rs->_fetchAllAssoc();
            if ($r) {
                foreach ($r as &$temp) {
                    $this->_castData($temp);
                }
            }
        }
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
        $query = 'INSERT INTO ' . $this->tableName
                . ' ('
                . $this->_getFieldListString()
                . ')'
                . ' VALUES ('
                . $this->_getSqlValueList($data)
                . ')';
        if ($this->conn->query($query)) {
            $r = $conn->insert_id;
        }
        return $r;
    }

    public function update($data) {
        $r = false;
        $query = 'UPDATE ' . $this->tableName
                . ' ('
                . $this->_getFieldListString()
                . ')'
                . ' VALUES ('
                . $this->_getSqlValueList($data)
                . ')'
                . " WHERE $id = '$id'";
        if ($this->conn->query($query)) {
            $n = $this->conn->affected_rows();
            switch($n) {
                case 0:
                    break;
                case 1:
                    $r = true;
                    break;
                default:
                    throw new Exception("Unexpected number of affected rows ($n)");
            }
        }
        return $r;
    }

    public function delete($id) {
        $r = false;
        $query = 'DELETE FROM ' . $this->tableName
                . " WHERE $id = '$id'";
        if ($this->conn->query($query)) {
            $n = $this->conn->affected_rows();
            switch($n) {
                case 0:
                    break;
                case 1:
                    $r = true;
                    break;
                default:
                    throw new Exception("Unexpected number of affected rows ($n)");
            }
        }
        return $r;
    }
}
