<?php

class VerificaRichiestaTesseramento extends MysqlProxyBase {
    public function __construct($connection) {
        parent::__construct($connection, 'verifica_richiesta_tesseramento', ['id',
            'idRichiestaTesseramento',
            'eseguitaIl',
            'esito',
            'idAmministratore']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];
        $data['idRichiestaTesseramento'] = (int) $data['idRichiestaTesseramento'];
        //TODO $data['eseguitaIl'] = new DateTime($data['eseguitaIl']);
        $data['idAmministratore'] = (int) $data['idAmministratore'];        
    }

}
