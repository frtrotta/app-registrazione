<?php

namespace dbproxy;

class Squadra extends MysqlProxyBase {

    public function __construct(&$connection) {
        parent::__construct($connection, 'squadra', ['id',
            'nome']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];
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
                    foreach ($temp as $t) {
                        $r[] = $ap->get($temp[0], $view);
                    }
                    $data['adesioniPersonali'] = $r;
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 71);
            }
        } else {
            throw new ClientRequestException('view requested', 70);
        }
    }

    protected function _isCoherent($data, $view) {
        if (!isset($data['nome'])
        ) {
            return 'nome is not set';
        }

        if (!$this->_is_integer_optional(@$data['id'])) {
            return 'is is set but it is not integer';
        }

        if (!$this->_is_string_with_length($data['nome'])) {
            return 'nome is a 0-length string';
        }

        if (isset($view)) {
            switch ($view) {
                case 'ordine':
                    if (!isset($data['idIscrizione'])) {
                        return 'idIscrizione is not set';
                    }
                    if (!is_integer($data['idIscrizione'])) {
                        return 'idIscrizione is not integer';
                    }

                    /* Nell'ordine c'è un'unica adesione personale, oppure
                     * non c'è per niente la squadra.
                     */

                    if (!isset($data['adesioniPersonali'])) {
                        return 'adesioniPersonali is not set';
                    }
                    if (!is_array($data['adesioniPersonali'])) {
                        return 'adesioniPersonali is not array';
                    }
                    if (count($data['adesioniPersonali']) !== 1) {
                        return 'adesioniPersonali must have only one element';
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

    public function add($data, $view) {
        $check = $this->_isCoherent($data, $view);
        if ($check !== true) {
            throw new ClientRequestException('Incoherent data for ' . get_class($this) . ". $check.", 93);
        }
        
        $r = $this->_baseAdd($data);


        if (isset($view)) {
            switch ($view) {
                case 'ordine':
                    // Se c'è la squadra, c'è un'unica adesione personale
                    $apProxy = new AdesionePersonale($this->conn);
                    $ap = $data['adesioniPersonali'][0];
                    $ap['idSquadra'] = $r[$this->fieldList[0]];
                    $apProxy->add($ap, $view);

                    $this->_addOptionalRelation('idIscrizione', $data['idIscrizione'], 'idSquadra', $r[$this->fieldList[0]], 'iscrizione__squadra');
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 50);
            }
        }
        
        return $this->get($this->fieldList[0], $view);
    }

}
