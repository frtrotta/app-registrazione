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

}
