<?php

class Invito extends MysqlProxyBase {
    public function __construct($connection) {
        parent::__construct($connection);
        
        $this->tableName = 'invito';

        $this->fieldList = ['codice',
            'nome',
            'email',
            'idIscrizione'];
    }

    protected function _castData(&$data) {
        $data['idIscrizione'] = (int) $data['idIscrizione'];
    }

}
