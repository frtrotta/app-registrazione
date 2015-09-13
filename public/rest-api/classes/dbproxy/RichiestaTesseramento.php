<?php

namespace dbproxy;

class RichiestaTesseramento extends MysqlProxyBase {
    public function __construct(&$connection) {
        parent::__construct($connection, 'richiesta_tesseramento', ['id',
            'eseguitaIl',
            'verificata',
            'idTipoRichiestaTesseramento',
            'idAdesionePersonale']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];
        //TODO $data['eseguitaIl'] = new DateTime($data['eseguitaIl']);
        $data['verificata'] = (boolean) $data['verificata'];
        $data['idTipoRichiestaTesseramento'] = (int) $data['idTipoRichiestaTesseramento'];
        $data['idAdesionePersonale'] = (int) $data['idAdesionePersonale'];        
    }

    protected function _complete(&$data) {
        $ap = new AdesionePersonale($this->conn);
        $data['adesionePersonale'] = $ap->get($data['idAdesionePersonale'], true);
        unset($data['idAdesionePersonale']);        
        
        $trt = new TipoRichiestaTesseramento($this->conn);
        $data['tipoRichiestaTesseramento'] = $trt->get($data['idTipoRichiestaTesseramento'], true);
        unset($data['idTipoRichiestaTesseramento']);
    }

    protected function _isCoherent($data) {
        if (!isset($data['id']) ||
                !isset($data['eseguitaIl']) ||
                !isset($data['verificata'])
        ) {
            return false;
        }
        if (!is_integer($data['id'])) {
            return false;
        }
        
        if (!$this->_is_datetime($data['eseguitaIl'])) {
            return false;
        }
        
        if (!is_bool($data['verificata'])) {
            return false;
        }
        
        return true;
    }
    
    protected function _removeUnsecureFields(&$data) {
        
    }
}
