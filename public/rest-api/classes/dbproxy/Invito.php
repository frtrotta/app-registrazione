<?php

namespace dbproxy;

class Invito extends MysqlProxyBase {

    public function __construct(&$connection) {
        parent::__construct($connection, 'invito', ['codice',
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
                case 'ordine':
                    break;

                default:
                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 71);
            }
        } else {
            throw new ClientRequestException('view requested', 70);
        }
    }

    protected function _isCoherent($data, $view) {
        if (!isset($data['codice']) ||
                !isset($data['nome']) ||
                !isset($data['cognome']) ||
                !isset($data['email']) ||
                !isset($data['idIscrizione'])
        ) {
            return 'At least one required field is missing';
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
        
    }

    public function add(&$data, $view) {
        $check = $this->_isCoherent($data, $view);
        if ($check !== true) {
            throw new ClientRequestException('Incoherent data for ' . get_class($this) . ". $check.", 93);
        }
        
        $r = $this->_baseAdd($data);
        $r = array_merge($data, $r);
        
        if (isset($view)) {
            switch ($view) {
                case 'ordine':
                    // Relazione con iscrizione aggiunta in _baseAdd
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 50);
            }
        }
        return $r;
    }

}
