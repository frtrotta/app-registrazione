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
        if (!array_key_exists('id', data) ||
                !isset($data['nome_it']) ||
                !isset($data['nome_en'])
        ) {
            return false;
        }
        if (!is_integer_optional($data['id'])) {
            return false;
        }        
        
        if(!$this->_is_string_with_length($data['nome_it'])) {
            return false;
        }
        
        if(!$this->_is_string_with_length($data['nome_en'])) {
            return false;
        }
        
        if(!$this->_is_string_with_length_optional(@$data['descrizione_it'])) {
            return false;
        }
        
        if(!$this->_is_string_with_length_optional(@$data['descrizione_it'])) {
            return false;
        }
        return true;
    }
    
    protected function _removeUnsecureFields(&$data) {
        
    }
}
