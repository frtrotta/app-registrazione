<?php

class Utente extends MysqlProxyBase {
    public function __construct($connection) {
        parent::__construct($connection);
        
        $this->tableName = 'Utente';

        $this->fieldList = ['id',
            'username',
            'password',
            'gettoneAutenticazione',
            'gettoneAutenticazioneScadeIl',
            'nome',
            'cognome',
            'sesso',
            'natoIl',
            'email',
            'email',
            'facebookId',
            'telefono',
            'eAmministratore'];
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];
        $data['eAmministratore'] = (boolean) $data['eAmministratore'];
    }

}
