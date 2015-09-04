<?php

class CodiceConclusioneGara extends MysqlProxyBase {
    public function __construct($connection) {
        parent::__construct($connection, 'codice_conclusione_gara', ['nome',
            'descrizione_it',
            'descrizione_en']);
    }

    protected function _castData(&$data) {
        
    }

}
