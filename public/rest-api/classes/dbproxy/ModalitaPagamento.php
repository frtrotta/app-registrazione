<?php

namespace dbproxy;

class ModalitaPagamento extends MysqlProxyBase {
    public function __construct(&$connection) {
        parent::__construct($connection, 'modalita_pagamento', ['id',
            'nome_it',
            'descrizione_it',
            'nome_en',
            'descrizione_en']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];  
    }

    protected function _complete(&$data) {
        
    }
    
    protected function _isCoherent($data) {
        if (!isset($data['id']) ||
                !isset($data['nome_it']) ||
                !isset($data['nome_en'])
        ) {
            return false;
        }
        if (!is_integer($data['id'])) {
            return false;
        }
        return true;
    }
    
    public function removeUnsecureFields(&$data) {
        
    }
}
