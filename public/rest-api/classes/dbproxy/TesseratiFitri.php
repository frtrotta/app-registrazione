<?php

class Risultato extends MysqlProxyBase {
    public function __construct($connection) {
        parent::__construct($connection, 'tesserati_fitri',['TESSERA',
            'CODICE_SS',
            'COGNOME',
            'NOME',
            'SESSO',
            'DATA_NASCITA',
            'CITTADINANZA', 
            'CATEGORIA',
            'QUALIFICA',
            'LIVELLO',
            'STATO', 
            'DATA_EMISSIONE',
            'TIPO_TESSERA', 
            'DISABILITA'] );
    }

    protected function _castData(&$data) {
        $data['TESSERA'] = (int) $data['TESSERA'];        
        $data['CODICE_SS'] = (int) $data['CODICE_SS'];
        //TODO $data['DATA_NASCITA'] = new DateTime($data['DATA_NASCITA']);
        //TODO $data['DATA_EMISSIONE'] = new DateTime($data['DATA_EMISSIONE']);
    }

}
