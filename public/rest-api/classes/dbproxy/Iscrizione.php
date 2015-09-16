<?php

namespace dbproxy;

class Iscrizione extends MysqlProxyBase {

    public function __construct(&$connection) {
        parent::__construct($connection, 'iscrizione', ['id',
            'eseguitaIl',
            'pattorale',
            'motto',
            'haImmagine',
            'idGara',
            'idOrdine']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];
        //XXX $data['eseguitaIl'] = new DateTime($data['eseguitaIl']);
        $data['pettorale'] = (int) $data['pettorale'];
        $data['haImmagine'] = (boolean) $data['haImmagine'];
        $data['idGara'] = (int) $data['idGara'];
        $data['idOrdine'] = (int) $data['idOrdine'];
    }

    protected function _complete(&$data, $view) {
        if (isset($view)) {
            $g = new Gara($this->conn);
            $data['gara'] = $g->get($data['idGara'], $view);
            unset($data['idGara']);

            $s = new Squadra($this->conn);
            $temp = $this->_getOptionalChildIds('idIscrizione', $data[$this->fieldList[0]], 'idSquadra', 'iscrizione__squadra');
            $n = count($temp);
            switch ($n) {
                case 0:
                    break;
                case 1:
                    $data['squadra'] = $s->get($temp[0], $view);
                    break;
                default:
                    throw new MysqlProxyBaseException("Unexpected child number ($n)", 30);
            }

            $ap = new AdesionePersonale($this->conn);
            $temp = $this->_getOptionalChildIds('idIscrizione', $data[$this->fieldList[0]], 'idAdesionePersonale', 'iscrizione__adesione_personale');
            $n = count($temp);
            switch ($n) {
                case 0:
                    break;
                case 1:
                    $data['adesionePersonale'] = $ap->get($temp[0], $view);
                    break;
                default:
                    throw new MysqlProxyBaseException("Unexpected child number ($n)", 30);
            }

            switch ($view) {
                case 'default':
                case 'invito':
                    $o = new Ordine($this->conn);
                    $data['ordine'] = $o->get($data['idOrdine'], $view);
                    unset($data['idOrdine']);
                    break;

                case 'iscrizione':
                    $r = new Risultato($this->conn);
                    $selectionClause = ['idIscrizione' => $data['id']];
                    $data['risultato'] = $r->getSelected($selectionClause, $view)[0];
                case 'ordine':

                    $i = new Inviti($this->conn);
                    $selectionClause = ['idIscrizione' => $data['id']];
                    $data['inviti'] = $i->getSelected($selectionClause, $view);
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 71);
            }
        } else {
            throw new ClientRequestException('view requested', 70);
        }
    }

    protected function _isCoherent($data, $view) {
        if (
                !isset($data['eseguitaIl']) ||
                !isset($data['haImmagine']) ||
                !isset($data['idOrdine']) ||
                !isset($data['idGara'])
        ) {
            return 'At least one required field is missing';
        }
        if (!$this->_is_integer_optional(@$data['id'])) {
            return 'is is set but it is not integer';
        }

        if (!is_integer($data['idOrdine'])) {
            return 'idOrdine is not integer';
        }

        if (!is_integer($data['idGara'])) {
            return 'idGara is not integer';
        }

        if (!$this->_is_datetime($data['eseguitaIl'])) {
            return 'eseguitaIl is not a valid datetime';
        }

        if (!$this->_is_integer_optional(@$data['pettorale'])) {
            return 'pettorale is set but it is not integer';
        }

        if (!$this->_is_string_with_length_optional(@$data['motto'])) {
            return 'motto is set but it is a 0-length string';
        }

        /* Must be either related to a squadra or to an adesione personale
         */
        if ((isset($data['squadra']) && isset($data['adesionePersonale'])) ||
                (!isset($data['squadra']) && !isset($data['adesionePersonale']))) {
            return 'Either squadra or adesionePersonale must be set';
        }

        if (isset($view)) {
            switch ($view) {
                case 'ordine':
                    // se ha un'adesione personale, non può avere un invito
                    // se ha un'adesione personale, non può avere una squadra
                    // se ha una squadra, deve avere almeno due inviti
                    // se ha una squadra con un'adesione personale, non può avere tre inviti (non implementato)

                    if (isset($data['adesionePersonale']) && isset($data['inviti'])) {
                        return 'adesionePersonale and inviti cannot be both set';
                    }

                    if (isset($data['squadra']) && !isset($data['inviti'])) {
                        return 'If squadra is set, inviti must be set as well';
                    }

                    if (isset($data['inviti'])) {
                        if (!is_array($data['inviti'])) {
                            return 'inviti is not an array';
                        }
                    }

                    if (isset($data['squadra']) && (count($data['inviti']) < 2 || count($data['inviti']) > 3)) {
                        return 'If squadra is set, the size of inviti can be either 2 or 3';
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
                    if (isset($data['inviti'])) {
                        // Questi inviti non possono avere alcuna adesione personale
                        $iProxy = new Invito($this->conn);
                        foreach ($data['inviti'] as &$i) {
                            $i['idIscrizione'] = $data['id'];
                            $ir = $iProxy->add($i, $view);
                            $i = array_merge($i, $ir);
                        }
                    }

                    if (isset($data['adesionePersonale'])) {
                        $aProxy = new AdesionePersonale($this->conn);
                        $data['adesionePersonale']['idIscrizione'] = $data['id'];
                        $ra = $aProxy->add($data['adesionePersonale'], $view);
                        $data['adesionePersonale'] = array_merge($data['adesionePersonale'], $ra);
                    }

                    if (isset($data['squadra'])) {
                        $sProxy = new Squadra($this->conn);
                        $data['squadra']['idIscrizione'] = $data['id'];
                        $rs = $sProxy->add($data['squadra'], $view);
                        $data['squadra'] = array_merge($data['squadra'], $ra);
                    }
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 50);
            }
        }
        return $r;
    }

}
