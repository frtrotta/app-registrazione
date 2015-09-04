<?php

class Squadra extends MysqlProxyBase {
    public function __construct($connection) {
        parent::__construct($connection, 'squadra', ['id',
            'nome']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];  
    }

}
