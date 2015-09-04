<?php

class AdesionePersonale extends MysqlProxyBase {
    public function __construct($connection) {
        parent::__construct($connection, 'adesione_personale', ['id',
            'indirizzoLinea1',
            'indirizzoLinea2',
            'indirizzoCap',
            'indirizzoCitta',
            'indirizzoProvincia',
            'indirizzoStato', 
            'categoriaFitri',
            'idUtente']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];
        $data['idUtente'] = (int) $data['idUtente'];
    }

}
