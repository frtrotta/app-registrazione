<?php

class ConfermaPagamento extends MysqlProxyBase {
    public function __construct($connection) {
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

}