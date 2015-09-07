<?php

namespace dbproxy;

class AbilitazioneTipoIscrizione extends MysqlProxyBase {
    public function __construct($connection) {
        parent::__construct($connection, 'abilitazione_tipo_iscrizione', ['idGara',
            'idTipoIscrizione',
            'finoAl',
            'costo']);
    }

    protected function _castData(&$data) {
        $data['idGara'] = (int) $data['idGara'];
        $data['idTipoIscrizione'] = (int) $data['idTipoIscrizione'];
        $data['costo'] = (float) $data['costo'];
        //TODO $data['finoAl'] = new DateTime($data['finoAl']);
    }

}
