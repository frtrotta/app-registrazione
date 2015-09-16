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
                    throw new ClientRequestException('Unsupported view for ' . getclass($this) . ': ' . $view, 71);
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
        
        if(isset($view)) {
            switch($view) {
                case 'ordine':
                    // Nell'ordine nessun invito può avere un'adesione personale
                    if(isset($data['idAdesionePersonale'])) {
                        return false;
                    }
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . getclass($this) . ': ' . $view, 60);
            }
        }

        return true;
    }

    protected function _removeUnsecureFields(&$data) {
        
    }

    public function add(&$data, $view) {
        if (!$this->_isCoherent($data, $view)) {
            throw new ClientRequestException('Incoherent data for ' . getclasse($this) . '. The data you provided did not meet expectations: please check and try again.', 93);
        }
        
        $r = $this->_baseAdd($data);
        $r = array_merge($data, $r);
        
        if (isset($view)) {
            switch ($view) {
                case 'ordine':
                    // Relazione con iscrizione aggiunta in _baseAdd
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . getclass($this) . ': ' . $view, 50);
            }
        }
        return $r;
    }

}
