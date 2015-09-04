<?php

class Squadra extends MysqlProxyBase {
    public function __construct($connection) {
        parent::__construct($connection, 'societa_fitri', ['codice',
            'nome']);
    }

    protected function _castData(&$data) {
        $data['codice'] = (int) $data['nome'];  
    }

}
