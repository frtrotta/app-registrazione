<?php

class RichiestaTesseramento extends MysqlProxyBase {
    public function __construct($connection) {
        parent::__construct($connection, 'richiesta_tesseramento', ['id',
            'eseguitaIl',
            'verificata',
            'idTipoRichiestaTesseramento',
            'idAdesionePersonale']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];
        //TODO $data['eseguitaIl'] = new DateTime($data['eseguitaIl']);
        $data['verificata'] = (boolean) $data['verificata'];
        $data['idTipoRichiestaTesseramento'] = (int) $data['idTipoRichiestaTesseramento'];
        $data['idAdesionePersonale'] = (int) $data['idAdesionePersonale'];        
    }

}
