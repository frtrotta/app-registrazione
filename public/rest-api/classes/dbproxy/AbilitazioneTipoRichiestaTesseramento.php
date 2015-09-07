<?php

namespace dbproxy;

class AbilitazioneTipoRichiestaTesseramento extends MysqlProxyBase {
    public function __construct($connection) {
        parent::__construct($connection, 'abilitazione_tipo_richiesta_tesseramento', ['idGara',
            'idTipoRichiestaTesseramento',
            'finoAl',
            'costo']);
    }

    protected function _castData(&$data) {
        $data['idGara'] = (int) $data['idGara'];
        $data['idTipoRichiestaTesseramento'] = (int) $data['idTipoRichiestaTesseramento'];
        $data['costo'] = (float) $data['costo'];
        //TODO $data['finoAl'] = new DateTime($data['finoAl']);
    }

}
