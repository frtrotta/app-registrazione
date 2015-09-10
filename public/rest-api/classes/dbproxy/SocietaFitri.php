<?php

namespace dbproxy;

class SocietaFitri extends MysqlProxyBase {
    public function __construct($connection) {
        parent::__construct($connection, 'societa_fitri', ['codice',
            'nome', 'provincia', 'email']);
    }

    protected function _castData(&$data) {
        $data['codice'] = (int) $data['codice'];  
    }
    
    protected function _complete(&$data) {
        
    }

    protected function _isCoherent($data) {
        if (!isset($data['codice']) ||
                !isset($data['nome'])
        ) {
            return false;
        }
        if (!is_integer($data['codice'])) {
            return false;
        }
        
        return true;
    }
}
