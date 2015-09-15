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
                case 'invito':
                    $i = new Iscrizione($this->conn);
                    $data['iscrizione'] = $i->get($data['idIscrizione'], $view);
                    unset($data['idIscrizione']);

                    $ap = new AdesionePersonale($this->conn);
                    $temp = $this->_getOptionalChildIds('idInvito', $data[$this->fieldList[0]], 'idAdesionePersonale', 'adesione_personale__invito');
                    $n = count($temp);
                    switch ($n) {
                        case 0:
                            break;
                        case 1:
                            $data['adesionePersonale'] = $s->get($temp[0], $view);
                            break;
                        default:
                            throw new MysqlProxyBaseException("Unexpected child number ($n)", 30);
                    }
                    break;
                case 'default':
                    $i = new Iscrizione($this->conn);
                    $data['iscrizione'] = $i->get($data['idIscrizione'], $view);
                    unset($data['idIscrizione']);
                case 'iscrizione':
                    $ap = new AdesionePersonale($this->conn);
                    $temp = $this->_getOptionalChildIds('idInvito', $data[$this->fieldList[0]], 'idAdesionePersonale', 'adesione_personale__invito');
                    $n = count($temp);
                    switch ($n) {
                        case 0:
                            break;
                        case 1:
                            $data['adesionePersonale'] = $s->get($temp[0], null);
                            break;
                        default:
                            throw new MysqlProxyBaseException("Unexpected child number ($n)", 30);
                    }
                    break;
                case 'ordine':
                    break;

                default:
                    throw new ClientRequestException('Unsupported view: ' . $view, 71);
            }
        } else {
            throw new ClientRequestException('view requested', 70);
        }
    }

    protected function _isCoherent($data) {
        if (!isset($data['codice']) ||
                !isset($data['nome']) ||
                !isset($data['cognome']) ||
                !isset($data['email']) ||
                !isset($data['idIscrizione'])
        ) {
            return false;
        }

        if (!$this->_is_string_with_length($data['codice'])) {
            return false;
        }

        if (!$this->_is_string_with_length($data['nome'])) {
            return false;
        }

        if (!$this->_is_string_with_length($data['cognome'])) {
            return false;
        }

        if (!$this->_is_string_with_length($data['email'])) {
            return false;
        }
        if (!is_integer($data['idIscrizione'])) {
            return false;
        }

        return true;
    }

    protected function _removeUnsecureFields(&$data) {
        
    }

}
