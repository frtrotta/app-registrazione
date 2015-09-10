<?php

namespace dbproxy;

class Utente extends MysqlProxyBase {

    public function __construct(&$connection) {
        parent::__construct($connection, 'utente', ['id',
            'password',
            'gettoneAutenticazione',
            'gettoneAutenticazioneScadeIl',
            'nome',
            'cognome',
            'sesso',
            'natoIl',
            'email',
            'facebookId',
            'telefono',
            'eAmministratore']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];
        $data['eAmministratore'] = (boolean) $data['eAmministratore'];
    }

    protected function _complete(&$data) {
        
    }
    
    protected function _isCoherent($data) {
        if (!isset($data['id']) ||
                !isset($data['nome']) ||
                !isset($data['cognome']) ||
                !isset($data['sesso']) ||
                !isset($data['natoIl']) ||
                !isset($data['email']) ||
                !isset($data['eAmministratore'])
        ) {
            return false;
        }
        if (!is_integer($data['id'])) {
            return false;
        }

        if (!is_bool($data['eAmministratore'])) {
            return false;
        }
        
        if (!$this->_is_date($data['natoIl'])) {
            return false;
        }
        
        // ---- opzionali
        
        if (!$this->_is_date_optional($data['gettoneAutenticazionScadeIl'])) {
            return false;
        }
        
        // --- combinazioni
        
        if((isset($data['password']) && isset($data['facebookId'])) ||
                (isset($data['facebookId']) && ! isset($data['password']))) {
            return false;
        }
        
        return true;
    }
}
