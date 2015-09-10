<?php

namespace dbproxy;

class ConfermaPagamento extends MysqlProxyBase {
    public function __construct(&$connection) {
        parent::__construct($connection, 'conferma_pagamento', ['id',
            'idOrdine',
            'eseguitaIl',
            'idAmministratore']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];
        $data['idOrdine'] = (int) $data['idOrdine'];
        //TODO $data['eseguitaIl'] = new DateTime($data['eseguitaIl']);
        $data['idAmministratore'] = (int) $data['idAmministratore'];        
    }

    protected function _complete(&$data) {
        $o = new Ordine($this->conn);
        $data['ordine'] = $o->get($data['idOrdine'], true);
        unset($data['idOrdine']);
        
        $a = new Utente($this->conn);
        $data['amministratore'] = $a->get($data['idAmministratore'], true);
        unset($data['idAmministratore']);
    }

    protected function _isCoherent($data) {
        if (!isset($data['id']) ||
                !isset($data['idOrdine']) ||
                !isset($data['idAmministratore']) ||
                !isset($data['eseguitaIl'])
        ) {
            return false;
        }
        if (!is_integer($data['id'])) {
            return false;
        }

        if (!is_integer($data['idOrdine'])) {
            return false;
        }

        if (!is_integer($data['idAmministratore'])) {
            return false;
        }
        
        if (!$this->_is_datetime($data['eseguitaIl'])) {
            return false;
        }
        
        return true;
    }
}