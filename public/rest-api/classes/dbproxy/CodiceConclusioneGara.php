<?php

namespace dbproxy;

class CodiceConclusioneGara extends MysqlProxyBase {
    public function __construct(&$connection) {
        parent::__construct($connection, 'codice_conclusione_gara', ['nome',
            'descrizione_it',
            'descrizione_en']);
    }

    protected function _castData(&$data) {
        
    }
    
    protected function _complete(&$data, $view) {
        if (isset($view)) {
            switch ($view) {
                case 'default':
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . getclass($this) . ': ' . $view, 71);
            }
        } else {
            throw new ClientRequestException('view requested', 70);
        }
    }
    
    protected function _isCoherent($data, $view) {
        if (!isset($data['nome']) ||
                !isset($data['descrizione_it']) ||
                !isset($data['descrizione_en'])
        ) {
            return false;
        }
        
        if(isset($view)) {
            switch($view) {
                default:
                    throw new ClientRequestException('Unsupported view for ' . getclass($this) . ': ' . $view, 60);
            }
        }
        
        return true;
    }
    
    protected function _removeUnsecureFields(&$data) {
        
    }

}
