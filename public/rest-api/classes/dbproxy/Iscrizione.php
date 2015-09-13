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
        //TODO $data['eseguitaIl'] = new DateTime($data['eseguitaIl']);
        $data['pettorale'] = (int) $data['pettorale'];
        $data['haImmagine'] = (boolean) $data['haImmagine'];
        $data['idGara'] = (int) $data['idGara'];
        $data['idOrdine'] = (int) $data['idOrdine'];
    }

    protected function _complete(&$data) {
        $g = new Gara($this->conn);
        $data['gara'] = $g->get($data['idGara'], true);
        unset($data['idGara']);

        $o = new Ordine($this->conn);
        $data['ordine'] = $o->get($data['idOrdine'], true);
        unset($data['idOrdine']);

        $s = new Squadra($this->conn);
        $temp = $this->_getOptionalChildIds('idIscrizione', $data[$this->fieldList[0]], 'idSquadra', 'iscrizione__squadra');
        $n = count($temp);
        switch ($n) {
            case 0:
                break;
            case 1:
                $data['squadra'] = $s->get($temp[0], true);
                break;
            default:
                throw new MysqlProxyBaseException("Unespected child number ($n)", 30);
        }

        $ap = new AdesionePersonale($this->conn);
        $temp = $this->_getOptionalChildIds('idIscrizione', $data[$this->fieldList[0]], 'idAdesionePersonale', 'iscrizione__adesione_personale');
        $n = count($temp);
        switch ($n) {
            case 0:
                break;
            case 1:
                $data['adesionePersonale'] = $ap->get($temp[0], true);
                break;
            default:
                throw new MysqlProxyBaseException("Unespected child number ($n)", 30);
        }
    }

    protected function _isCoherent($data) {
        if (!isset($data['id']) ||
                !isset($data['eseguitaIl']) ||
                !isset($data['haImmagine']) ||
                !isset($data['idOrdine']) ||
                !isset($data['idGara'])
        ) {
            return false;
        }
        if (!is_integer($data['id'])) {
            return false;
        }

        if (!is_integer($data['idOrdine'])) {
            return false;
        }

        if (!is_integer($data['idGara'])) {
            return false;
        }

        if (!$this->_is_datetime($data['eseguitaIl'])) {
            return false;
        }

        /* Must be either related to a squadra or to an adesione personale
         */
        if( (isset($data['squadra']) && isset($data['adesionePersonale'])) ||
            (!isset($data['squadra']) &&!isset($data['adesionePersonale']))) {
            return false;
        }

        return true;
    }
    
    protected function _removeUnsecureFields(&$data) {
        
    }

}
