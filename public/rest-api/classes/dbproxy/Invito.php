<?php

namespace dbproxy;

class Invito extends MysqlProxyBase {
    public function __construct($connection) {
        parent::__construct($connection, 'invito', ['codice',
            'nome',
            'cognome',
            'email',
            'idIscrizione']);
    }

    protected function _castData(&$data) {
        $data['idIscrizione'] = (int) $data['idIscrizione'];
    }

}
