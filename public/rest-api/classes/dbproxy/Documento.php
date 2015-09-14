<?php

namespace dbproxy;

class Documento extends MysqlProxyBase {
    public function __construct(&$connection) {
        parent::__construct($connection, 'documento', ['nomeFile',
            'idRichiestaTesseramento']);
    }

    protected function _castData(&$data) {
        $data['idRichiestaTesseramento'] = (int) $data['idRichiestaTesseramento'];
    }
    
    protected function _complete(&$data) {
        $rt = new RichiestaTesseramento($this->conn);
        $data['richiestaDiTesseramento'] = $rt->get($data['idRichiestaTesseramento'], true);
        unset($data['idRichiestaTesseramento']);
    }

    protected function _isCoherent($data) {
        if (!isset($data['nomeFile']) ||
                !isset($data['idRichiestaTesseramento'])
        ) {
            return false;
        }        
        
        if(!$this->_is_string_with_length($data['nomeFile'])) {
            return false;
        }
        if (!is_integer($data['idRichiestaTesseramento'])) {
            return false;
        }
        
        return true;
    }
    
    protected function _removeUnsecureFields(&$data) {
        
    }

}
