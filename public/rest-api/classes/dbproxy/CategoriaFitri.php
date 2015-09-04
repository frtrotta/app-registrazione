<?php

class CategoriaFitri extends MysqlProxyBase {
    public function __construct($connection) {
        parent::__construct($connection, 'categoria_fitri', ['nome',
            'nome_esteso',
            'iniza_da_anni']);
    }

    protected function _castData(&$data) {
        $data['inizia_da_anni'] = (int) $data['inizia_da_anni'];
    }

}
