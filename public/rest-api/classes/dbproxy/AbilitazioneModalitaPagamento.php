<?php

namespace dbproxy;

class AbilitazioneModalitaPagamento extends MysqlProxyBase {
    public function __construct($connection) {
        parent::__construct($connection, 'abilitazione_modalita_pagamento', ['idGara',
            'idModalitaPagamento',
            'finoAl']);
    }

    protected function _castData(&$data) {
        $data['idGara'] = (int) $data['idGara'];
        $data['idModalitaPagamento'] = (int) $data['idModalitaPagamento'];        
        //TODO $data['finoAl'] = new DateTime($data['finoAl']);
    }

}
