<?php

namespace dbproxy;

class Iscrizione extends MysqlProxyBase {
    public function __construct($connection) {
        parent::__construct($connection, 'iscrizione', ['id',
            'eseguitaIl',
            'pattorale',
            'motto',
            'haImmagine',
            'idGara',
            'idOrdine']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];
        //TODO $data['eseguitaIl'] = new DateTime($data['eseguitaIl']);
        $data['pettorale'] = (int) $data['pettorale'];
        $data['haImmagine'] = (boolean) $data['haImmagine'];
        $data['idGara'] = (int) $data['idGara'];
        $data['idOrdine'] = (int) $data['idOrdine'];
    }

}
