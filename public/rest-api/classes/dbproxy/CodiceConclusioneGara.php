<?php

namespace dbproxy;

class CodiceConclusioneGara extends MysqlProxyBase {
    public function __construct($connection) {
        parent::__construct($connection, 'codice_conclusione_gara', ['nome',
            'descrizione_it',
            'descrizione_en']);
    }

    protected function _castData(&$data) {
        
    }
    
    protected function _complete(&$data) {
        
    }
    
    protected function _isCoherent($data) {
        if (!isset($data['nome']) ||
                !isset($data['descrizione_it']) ||
                !isset($data['descrizione_en'])
        ) {
            return false;
        }
        
        return true;
    }

}
