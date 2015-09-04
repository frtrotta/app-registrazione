<?php

class Risultato extends MysqlProxyBase {
    public function __construct($connection) {
        parent::__construct($connection, 'risultato',['id',
            'codiceConclusioneGara',
            'posizione',
            'posizioneInSesso',
            'posizioneInCategoria',
            'tempo',
            'posizioneDopoNuoto', 
            'tempoDopoNuoto',
            'posizioneDopoBici',
            'tempoDopoBici',
            'posizioneFrazioneBici', 
            'tempoFrazioneBici',
            'posizioneFrazioneCorsa', 
            'tempoFrazioneCorsa',
            'idIscrizione'] );
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];        
        $data['idUtente'] = (int) $data['idUtente'];
    }

}
