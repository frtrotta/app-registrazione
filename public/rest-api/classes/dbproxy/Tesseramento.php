<?php

namespace dbproxy;

class Tesseramento extends MysqlProxyBase {
    public function __construct($connection) {
        parent::__construct($connection, 'tesseramento', ['id',
            'finoAl',
            'matricola',
            'stranieroSocieta',
            'stranieroStato',
            'idTipoTesseramento']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];
        //TODO $data['finoAl'] = new DateTime($data['finoAl']);
        $data['idTipoTesseramento'] = (int) $data['idTipoTesseramento'];      
    }

}
