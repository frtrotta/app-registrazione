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
                    foreach ($temp as $t) {
                        $r[] = $ap->get($temp[0], $view);
                    }
                    $data['adesioniPersonali'] = $r;
                default:
                    throw new ClientRequestException('Unsupported view for ' . getclass($this) . ': ' . $view, 71);
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

        if (!$this->is_integer_optional($data['id'])) {
            return false;
        }

        if (!$this->_is_string_with_length($data['nome'])) {
            return false;
        }

        if (isset($view)) {
            switch ($view) {
                case 'ordine':
                    if (!isset($data['idIscrizione'])) {
                        return false;
                    }
                    if (!is_integer($data['idIscrizione'])) {
                        return false;
                    }

                    /* Nell'ordine c'è un'unica adesione personale, oppure
                     * non c'è per niente la squadra.
                     */

                    if (!isset($data['adesioniPersonali'])) {
                        return false;
                    }
                    if (!is_array($data['adesioniPersonali'])) {
                        return false;
                    }
                    if (count($data['adesioniPersonali']) !== 1) {
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
                    // Se c'è la squadra, c'è un'unica adesione personale
                    $apProxy = new AdesionePersonale($this->conn);
                    $ap = $data['adesioniPersonali'][0];
                    $ap['idSquadra'] = $data['id'];
                    $apr = $apProxy->add($ap, $view);
                    $ap = array_merge($ap, $apr);

                    $this->_addOptionalRelation('idIscrizione', $data['idIscrizione'], 'idSquadra', $data['id'], 'iscrizione__squadra');
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . getclass($this) . ': ' . $view, 50);
            }
        }
        return $r;
    }

}
