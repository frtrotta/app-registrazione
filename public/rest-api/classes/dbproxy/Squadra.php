<?php

namespace dbproxy;

class Squadra extends MysqlProxyBase {

    public function __construct(&$connection) {
        parent::__construct($connection, 'societa_fitri', ['codice',
            'nome']);
    }

    protected function _castData(&$data) {
        $data['codice'] = (int) $data['nome'];
    }

    protected function _complete(&$data, $view) {
        if (isset($view)) {
            switch ($view) {
                case 'default':
                    break;
                case 'invito':
                case 'iscrizione':
                case 'ordine':
                    $ap = new AdesionePersonale($this->conn);
                    $temp = $this->_getOptionalChildIds('idSquadra', $data[$this->fieldList[0]], 'idAdesionePersonale', 'adesione_personale__squadra');
                    $r = [];
                    foreach($temp as $t) {
                        $r[] = $ap->get($temp[0], $view);
                    }
                    $data['adesioniPersonali'] = $r;
                default:
                    throw new ClientRequestException('Unsupported view: ' . $view, 71);
            }
        } else {
            throw new ClientRequestException('view requested', 70);
        }
    }

    protected function _isCoherent($data, $view) {
        if (
                !isset($data['nome'])
        ) {
            return false;
        }

        if (!is_integer_optional($data['id'])) {
            return false;
        }

        if (!$this->_is_string_with_length($data['nome'])) {
            return false;
        }
        
        if(isset($view)) {
            switch($view) {
                default:
                    throw new ClientRequestException('Unsupported view: ' . $view, 60);
            }
        }

        return true;
    }

    protected function _removeUnsecureFields(&$data) {
        
    }

}
