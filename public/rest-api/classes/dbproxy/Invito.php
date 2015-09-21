<?php

namespace dbproxy;

class Invito extends MysqlProxyBase {

    public function __construct(&$connection) {
        parent::__construct($connection, 'invito', [
            'id',
            'codice',
            'nome',
            'cognome',
            'email',
            'idIscrizione']);
    }

    protected function _castData(&$data) {
        $data['idIscrizione'] = (int) $data['idIscrizione'];
    }

    protected function _complete(&$data, $view) {
        if (isset($view)) {
            switch ($view) {
                case 'default':
                    $i = new Iscrizione($this->conn);
                    $data['iscrizione'] = $i->get($data['idIscrizione'], $view);
                    unset($data['idIscrizione']);
                case 'iscrizione':
                case 'invito':
                    $i = new Iscrizione($this->conn);
                    $data['iscrizione'] = $i->get($data['idIscrizione'], $view);
                    unset($data['idIscrizione']);
                case 'ordine':
                    
                    $temp = $this->_getOptionalChildIds('idInvito', $data[$this->fieldList[0]], 'idAdesionePersonale', 'adesione_personale__invito');
                    $n = count($temp);
                    switch ($n) {
                        case 0:
                            break;
                        case 1:
                            $data['idAdesionePersonale'] = $temp[0];
                            break;
                        default:
                            throw new MysqlProxyBaseException("Unexpected child number ($n)", 30);
                    }                    
                    break;

                default:
                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 71);
            }
        } else {
            throw new ClientRequestException('view requested', 70);
        }
    }

    protected function _isCoherent($data, $view) {
        if (!isset($data['codice'])) {
            return 'codice is missing';
        }
        if (!isset($data['nome'])) {
            return 'nome is missing';
        }
        if (!isset($data['cognome'])) {
            return 'cognome is missing';
        }
        if (!isset($data['email'])) {
            return 'email is missing';
        }
        if (!isset($data['idIscrizione'])) {
            return 'idIscrizione is missing';
        }
        
        if (!$this->_is_string_with_length($data['codice'])) {
            return 'codice is a 0-length string';
        }

        if (!$this->_is_string_with_length($data['nome'])) {
            return 'nome is a 0-length string';
        }

        if (!$this->_is_string_with_length($data['cognome'])) {
           return 'cognome is a 0-length string';
        }

        if (!$this->_is_string_with_length($data['email'])) {
            return 'email is a 0-length string';
        }
        if (!is_integer($data['idIscrizione'])) {
            return 'idIscrizione is not integer';
        }
        
        if(isset($view)) {
            switch($view) {
                case 'ordine':
                    // Nell'ordine nessun invito può avere un'adesione personale
                    if(isset($data['idAdesionePersonale'])) {
                        return 'idAdesionePersonale cannot be set';
                    }
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 60);
            }
        }

        return true;
    }

    protected function _removeUnsecureFields(&$data) {
        unset($data['codice']);
    }
    
    private function _generateCodice() {
        $unique = false;
        while(!$unique) {
            $codice = sha1(microtime() . 'evviva il kite');
            $query = 'SELECT '
                    . ' codice'
                    . ' FROM invito'
                    . " WHERE codice = '$codice'";
            $rs = $this->conn->query($query);
            if ($this->conn->errno) {
                throw new MysqlProxyBaseException($this->conn->error, $this->conn->errno);
            }
            if ($rs) {
                if ($rs->num_rows === 0) {
                    $unique = true;
                    $rs->free();
                }
            } else {
                throw new MysqlProxyBaseException($this->conn->error, $this->conn->errno);
            }
        }
        return $codice;
    }

    public function add($data, $view) {
        $data['codice'] = $this->_generateCodice();
        
        $check = $this->_isCoherent($data, $view);
        if ($check !== true) {
            throw new ClientRequestException('Incoherent data for ' . get_class($this) . ". $check.", 93);
        }
        
        $r = $this->_baseAdd($data);
        
        if (isset($view)) {
            switch ($view) {
                case 'ordine':
                    // Relazione con iscrizione aggiunta in _baseAdd
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 50);
            }
        }
        
        return $this->get($this->fieldList[0], $view);
    }

}
