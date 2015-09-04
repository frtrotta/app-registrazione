<?php

class TipoIScrizione extends MysqlProxyBase {
    public function __construct($connection) {
        parent::__construct($connection, 'tipo_iscrizione', ['id',
            'nome_it',
            'descrizione_it',
            'nome_en',
            'descrizione_en']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];  
    }

}