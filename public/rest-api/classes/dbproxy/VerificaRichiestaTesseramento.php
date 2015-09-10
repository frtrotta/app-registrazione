<?php

namespace dbproxy;

class VerificaRichiestaTesseramento extends MysqlProxyBase {
    public function __construct(&$connection) {
        parent::__construct($connection, 'verifica_richiesta_tesseramento', ['id',
            'idRichiestaTesseramento',
            'eseguitaIl',
            'esito',
            'idAmministratore']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];
        $data['idRichiestaTesseramento'] = (int) $data['idRichiestaTesseramento'];
        //TODO $data['eseguitaIl'] = new DateTime($data['eseguitaIl']);
        $data['idAmministratore'] = (int) $data['idAmministratore'];        
    }    

    protected function _complete(&$data) {
        $sf = new SocietaFitri($this->conn);
        $data['societa'] = $sf->get($data['CODICE_SS'], true);
        unset($data['CODICE_SS']);
    }

    protected function _isCoherent($data) {
        if (!isset($data['id']) ||
                !isset($data['eseguitaIl']) ||
                !isset($data['esito']) ||
                !isset($data['idRichiestaTesseramento']) ||
                !isset($data['idAmministratore'])
        ) {
            return false;
        }
        if (!is_integer($data['id'])) {
            return false;
        }
        if (!is_integer($data['idRichiestaTesseramento'])) {
            return false;
        }

        if (!is_integer($data['idAmministratore'])) {
            return false;
        }
        
        if (!$this->_is_date($data['eseguitaIl'])) {
            return false;
        }
        
        return true;
    }
}
