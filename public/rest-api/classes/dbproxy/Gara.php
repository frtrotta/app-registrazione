<?php

namespace dbproxy;

class Gara extends MysqlProxyBase {
    public function __construct($connection) {
        parent::__construct($connection, 'gara', ['id',
            'nome',
            'descrizione_it',
            'descrizione_en',
            'disputataIl',
            'idTipoGara']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];
        $data['idTipoGara'] = (int) $data['idTipoGara'];
        //TODO $data['disputataIl'] = new DateTime($data['disputataIl']);
    }

}
