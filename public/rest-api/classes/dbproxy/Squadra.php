<?php

class Squadra extends MysqlProxyBase {
    public function __construct($connection) {
        parent::__construct($connection);
        
        $this->tableName = 'squadra';

        $this->fieldList = ['id',
            'nome'];
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];  
    }

}
