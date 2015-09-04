<?php

class Ordine extends MysqlProxyBase {
    public function __construct($connection) {
        parent::__construct($connection, 'ordine', ['id',
            'ricevutoIl',
            'totale',
            'pagato',
            'ricevutaInviata',
            'ricevutaInviataIl',
            'note',
            'indirizzoLinea1',
            'indirizzoLinea2',
            'indirizzoCap',
            'indirizzoCitta',
            'indirizzoProvincia',
            'indirizzoStato', 
            'idCliente',
            'idModalitaPagamento']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];
        //TODO $data['ricevutoIl'] = new DateTime($data['ricevutoIl']);
        $data['totale'] = (float) $data['totale'];
        $data['pagato'] = (boolean) $data['pagato'];        
        $data['ricevutaInviata'] = (boolean) $data['ricevutaInviata'];
        //TODO $data['ricevutaInviataIl'] = new DateTime($data['ricevutaInviataIl']);
        $data['idCliente'] = (int) $data['idCliente'];
        $data['idModalitaPagamento'] = (int) $data['idModalitaPagamento'];
    }

}
