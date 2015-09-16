<?php

namespace dbproxy;

namespace dbproxy;

abstract class MysqlProxyBase {

    private $tableName; // the name of the table
    private $fieldList;
    /* The list of the fields in the correspondent SQL table
     * The first must be the IDENTITY field.
     */
    protected $conn;

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

    protected function _sqlFormat($field) {
        $r = 'NULL';
        if (isset($field)) {
            if (is_string($field)) {
                $field = $this->conn->escape_string($field);
                $r = "'$field'";
            } else if (is_bool($field)) {
                $r = ($field) ? '1' : '0';
            } else {
                $r = (string) $field;
                // XXX possibile convertire facilmente data in stringa? e data e ora?
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
                                    throw new ClientRequestException('Unexpected operator definition (' . $opDefinition . ')', 1);
                            }
                            $value = $value[$opDefinition];
                        } else {
                            throw new ClientRequestException('Malformed clause ' . var_export($value, true), 2);
                        }
                    } else {
                        throw new ClientRequestException('Malformed clause ' . var_export($value, true), 3);
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
            throw new ClientRequestException('limit must be integer', 10);
        }
        if (isset($pars['skip'])) {
            if (!is_integer($pars['skip'])) {
                throw new ClientRequestException('skip must be integer', 11);
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
     * Complete the data with the possibile related data, according to the view
     */
    abstract protected function _complete(&$data, $view);

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
            throw new MysqlProxyBaseException($this->conn->error, $this->conn->errno);
        }
        return $this->fetch_all();
    }
    
    protected function _addOptionalRelation($idField, $idValue, $childIdField, $childIdValue, $tableName) {
        $idValue = $this->_sqlFormat($idValue);
        $childIdValue = $this->_sqlFormat($childIdValue);
        $query = "INSERTO INTO `$tableName`"
                . " (`$idField`, `$childIdField`)"
                . " VALUES"
                . " ($idValue, $childIdValue)";
        $rs = $this->conn->query($query);
        if ($this->conn->errno) {
            throw new MysqlProxyBaseException($this->conn->error, $this->conn->errno);
        }
    }

    abstract protected function _isCoherent($data, $view);

    abstract protected function _removeUnsecureFields(&$data);

    protected function _unsetField(&$set, $fieldName) {
        foreach ($set as &$temp) {
            unset($temp[$fieldName]);
        }
    }

    /**
     * 
     * @param mixed $id
     * @param string $view: either null for no completion, or the name of the view
     * @param boolean $removeUnsecureFields
     * @return associative array
     * @throws MysqlProxyBaseException
     */
    public function get($id, $view = null, $removeUnsecureFields = true) {
        $r = null;
        $query = 'SELECT '
                . $this->_getFieldListString()
                . ' FROM `' . $this->tableName . '` '
                . ' WHERE ' . $this->fieldList[0] . ' = ' . $this->_sqlFormat($id);
        $rs = $this->conn->query($query);
        if ($this->conn->errno) {
            throw new MysqlProxyBaseException($this->conn->error, $this->conn->errno);
        }
        $r = $rs->fetch_assoc();
        if ($r) {
            $this->_castData($r);
            if ($view) {
                $this->_complete($r, $view);
            }
            if ($removeUnsecureFields) {
                $this->_removeUnsecureFields($r);
            }
        }
        return $r;
    }
    
    /**
     * 
     * @param associative array $selectionClause
     * @param string $view: either null for no completion, or the name of the view
     * @param boolean $removeUnsecureFields
     * @return array of associative arrays
     * @throws ClientRequestException
     * @throws MysqlProxyBaseException
     */
    public function getSelected($selectionClause, $view = null, $removeUnsecureFields = true) {
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
                if ($this->conn->errno === 1054) {
                    // One ore more fields have not the correct name
                    throw new ClientRequestException($this->conn->error, $this->conn->errno);
                }
                throw new MysqlProxyBaseException($this->conn->error, $this->conn->errno);
            }
            $r = $this->_fetchAllAssoc($rs);
        }

        if ($r) {
            foreach ($r as &$temp) {
                $this->_castData($temp);
                if ($view) {
                    $this->_complete($temp, $view);
                }

                if ($removeUnsecureFields) {
                    $this->_removeUnsecureFields($temp);
                }
            }
        }
        return $r;
    }

    /**
     * 
     * @param string $view: either null for no completion, or the name of the view
     * @param boolean $removeUnsecureFields
     * @param integer $limit
     * @return array of associative arrays
     * @throws MysqlProxyBaseException
     */
    public function getAll($view = null, $removeUnsecureFields = true, $limit = 50) {
        $r = null;
        $query = 'SELECT '
                . $this->_getFieldListString()
                . ' FROM `' . $this->tableName . '` '
                . " LIMIT $limit";
        $rs = $this->conn->query($query);
        if ($this->conn->errno) {
            throw new MysqlProxyBaseException($this->conn->error, $this->conn->errno);
        }
        $r = $this->_fetchAllAssoc($rs);

        if ($r) {
            foreach ($r as &$temp) {
                $this->_castData($temp);
                if ($view) {
                    $this->_complete($temp, $view);
                }
                if ($removeUnsecureFields) {
                    $this->_removeUnsecureFields($temp);
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

    private function _createSetList($data) {
        $first = true;
        foreach ($data as $key => $value) {
            $key = $this->conn->escape_string($key);
            if ($first) {
                $first = false;
                $r = " SET `$key` = " . $this->_sqlFormat($value);
            } else {
                $r .= ", `$key` = " . $this->_sqlFormat($value);
            }
        }
        return $r;
    }

    protected function _is_string_with_length($value) {
        return is_string($value) && (strlen($value) > 0);
    }
    
    protected function _is_string_with_length_optional($value) {
        if (isset($value)) {
            return $this->_is_string_with_length($value);
        } else {
            return true;
        }
    }

    protected function _is_date($value) {
        $format = ['Y/m/d', 'Y-m-d'];
        for ($i = 0; $i < count($format); $i++) {
            $d = \DateTime::createFromFormat($format[$i], $value);
            if ($d) {
                break;
            }
        }
        if ($d) {
            return $d->format($format[$i]) === $value;
        } else {
            return false;
        }
    }

    protected function _is_date_optional($value) {
        if (isset($value)) {
            return $this->_is_date($value);
        } else {
            return true;
        }
    }

    protected function _is_datetime($value) {
        $format = ['Y/m/d H:i:s', 'Y-m-d H:i:s'];
        for ($i = 0; $i < count($format); $i++) {
            $d = \DateTime::createFromFormat($format[$i], $value);
            if ($d) {
                break;
            }
        }
        if ($d) {
            return $d->format($format[$i]) === $value;
        } else {
            return false;
        }
    }

    protected function _is_datetime_optional($value) {
        if (isset($value)) {
            return $this->_is_datetime($value);
        } else {
            return true;
        }
    }

    protected function _is_integer_optional($value) {
        if (isset($value)) {
            return $this->is_integer($value);
        } else {
            return true;
        }
    }

    protected function _is_bool_optional($value) {
        if (isset($value)) {
            return is_bool($value);
        } else {
            return true;
        }
    }

    protected function _is_float_optional($value) {
        if (isset($value)) {
            return $this->is_float($value);
        } else {
            return true;
        }
    }    
    
    public function add(&$data, $view) {
        throw new \Exception('Method not implemented');
    }

    /**
     * Add a new record to the table.
     * The field of $data correspondent to the AUTO_INCREMENT field can be either set to null or not set.
     * 
     * @param associative array $data
     * @return $data with the generated identifier
     */
    protected function _baseAdd($data) {
//        if ($this->_isCoherent($data, null)) {  //TODO è veramente necessario controllare qui?
//                /* Se add diventa una funzione che deve essere richiamata da update della specifica
//                 * classe derivata, allora sarà compito di questa verificare che tutto sia coerente.
//                 * Guarda, però, come è implementato il metodo add di Utente
//                 */
            $query = 'INSERT INTO `' . $this->tableName . '` '
                    . $this->_createFieldListAndValues($data);
            $this->conn->query($query);
            switch ($this->conn->errno) {
                case 0:
                    break;
                case 1062:
                    throw new ClientRequestException('Instance already exists', 50);
                    break; //...
                default:
                    throw new MysqlProxyBaseException($this->conn->error, $this->conn->errno);
            }
            $r = $data;
            $r[$this->fieldList[0]] = $this->conn->insert_id;
//        } else {
//            throw new ClientRequestException('Incoherent data for ' . getclasse($this) . '. The data you provided did not meet expectations: please check and try again.', 92);
//        }
        return $r;
    }    
    
    public function update($id, $data) {
        throw new \Exception('Method not implemented');
    }

    /**
     * @param mixed $id
     * @param associative array $data
     * @return null or the updated (passed) data
     * @throws Exception
     */
    protected function _baseUpdate($id, $data) {
        $r = null;
        $current = $this->get($id);
        if ($current) {
            $data = array_merge($current, $data);
//            if ($this->_isCoherent($data, null)) {  //TODO è veramente necessario controllare qui?
//                /* Se update diventa una funzione che deve essere richiamata da update della specifica
//                 * classe derivata, allora sarà compito di questa verificare che tutto sia coerente.
//                 * Guarda, però, come è implementato il metodo add di Utente
//                 */
                $identifierFieldName = $this->fieldList[0];
                unset($data[$identifierFieldName]);
                $identifierFieldValue = $id;
                $query = 'UPDATE `' . $this->tableName . '` '
                        . $this->_createSetList($data)
                        . ' WHERE `' . $identifierFieldName . '` = ' . $this->_sqlFormat($identifierFieldValue);
                $this->conn->query($query);

                if ($this->conn->errno) {
                    throw new MysqlProxyBaseException($this->conn->error, $this->conn->errno);
                }

                $n = $this->conn->affected_rows;
                switch ($n) {
                    case 0:
                        break;
                    case 1:
                        $r = $data;
                        $r[$identifierFieldName] = $identifierFieldValue;
                        break;
                    default:
                        throw new MysqlProxyBaseException("Unexpected number of affected rows ($n)");
                }
//            } else {
//                throw new ClientRequestException('Incoherent data for ' . getclasse($this) . '. The data you provided did not meet expectations: please check and try again.', 91);
//            }
        }

        return $r;
    }
    
    public function delete($id) {
        throw new \Exception('Method not implemented');
    }

    protected function _deleteBase($id) {
        $r = false;
        $query = 'DELETE FROM `' . $this->tableName . '` '
                . ' WHERE ' . $this->fieldList[0] . ' = ' . $this->_sqlFormat($id);
        $this->conn->query($query);
        if ($this->conn->errno) {
            throw new MysqlProxyBaseException($this->conn->error, $this->conn->errno);
        }
        $n = $this->conn->affected_rows();
        switch ($n) {
            case 0:
                break;
            case 1:
                $r = true;
                break;
            default:
                throw new MysqlProxyBaseException("Unexpected number of affected rows ($n)");
        }
        return $r;
    }

}
