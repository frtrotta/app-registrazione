<?php

class ModalitaPagamento extends MysqlProxyBase {
    public function __construct($connection) {
        parent::__construct($connection, 'modalita_pagamento', ['id',
            'nome_it',
            'descrizione_it',
            'nome_en',
            'descrizione_en']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];  
    }

}
