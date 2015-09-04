<?php

class Documento extends MysqlProxyBase {
    public function __construct($connection) {
        parent::__construct($connection, 'documento', ['nomeFile',
            'idRichiestaTesseramento']);
    }

    protected function _castData(&$data) {
        $data['idRichiestaTesseramento'] = (int) $data['idRichiestaTesseramento'];
    }

}
