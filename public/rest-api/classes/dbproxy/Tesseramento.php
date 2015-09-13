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
                !isset($data['idTipoTesseramento'])
                
        ) {
            return false;
        }
        if (!is_integer($data['id'])) {
            return false;
        }
        
        if (!$this->_is_datetime($data['finoAl'])) {
            return false;
        }
        
        if (!is_integer($data['idTipoTesseramento'])) {
            return false;
        }
        
        if (!is_integer_optional($data['codiceSocietaFitri'])) {
            return false;
        }
        
        if(!$this->_is_string_with_length_optional(@$data['matricola'])) {
            return false;
        }
        
        if(!$this->_is_string_with_length_optional(@$data['stranieroSocieta'])) {
            return false;
        }
        
        if(!$this->_is_string_with_length_optional(@$data['stranieroStato'])) {
            return false;
        }
        
        // combinazioni
        if(
                !(is_integer(@$data['codiceSocietaFitri']) &&
                $this->_is_string_with_length(@$data['matricola']))
                
                &&
                
                !($this->_is_string_with_length(@$data['matricola']) &&
                $this->_is_string_with_length(@$data['stranieroSocieta']) &&
                $this->_is_string_with_length(@$data['stranieroStato']))
                
                &&
                
                !(isset($data['matricola']) &&
                isset($data['stranieroSocieta']) &&
                isset($data['stranieroStato']))
                ) {
            return false;
        }
        
        return true;
    }
    
    protected function _removeUnsecureFields(&$data) {
        
    }
}
