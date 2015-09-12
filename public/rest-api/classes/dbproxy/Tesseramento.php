<?php

namespace dbproxy;

class Tesseramento extends MysqlProxyBase {
    public function __construct(&$connection) {
        parent::__construct($connection, 'tesseramento', ['id',
            'finoAl',
            'matricola',
            'stranieroSocieta',
            'stranieroStato',
            'idTipoTesseramento']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];
        //TODO $data['finoAl'] = new DateTime($data['finoAl']);
        $data['idTipoTesseramento'] = (int) $data['idTipoTesseramento'];      
    }

    
    protected function _complete(&$data) {
        
    }

    protected function _isCoherent($data) {
        if (!isset($data['id']) ||
                !isset($data['finoAl']) ||
                !isset($data['codiceSocietaFitri']) ||
                !isset($data['idTipoTesseramento'])
                
        ) {
            return false;
        }
        if (!is_integer($data['id'])) {
            return false;
        }
        if (!is_integer($data['codiceSocietaFitri'])) {
            return false;
        }
        if (!is_integer($data['idTipoTesseramento'])) {
            return false;
        }
        
        if (!$this->_is_datetime($data['finoAl'])) {
            return false;
        }
        
        return true;
    }
    
    protected function _removeUnsecureFields(&$data) {
        
    }
}
