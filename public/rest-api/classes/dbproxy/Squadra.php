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
    
    protected function _complete(&$data) {
        
    }

    protected function _isCoherent($data) {
        if (
                !isset($data['nome'])
        ) {
            return false;
        }
        
        if (!is_integer_optional($data['id'])) {
            return false;
        }
        
        if(!$this->_is_string_with_length($data['nome'])) {
            return false;
        }
        
        return true;
    }
    
    protected function _removeUnsecureFields(&$data) {
        
    }
}
