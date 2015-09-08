<?php

namespace dbproxy;

class AbilitazioneModalitaPagamento extends MysqlProxyBase {
    public function __construct($connection) {
        parent::__construct($connection, 'abilitazione_modalita_pagamento', ['idGara',
            'idModalitaPagamento',
            'finoAl']);
    }

    protected function _castData(&$data) {
        $data['idGara'] = (int) $data['idGara'];
        $data['idModalitaPagamento'] = (int) $data['idModalitaPagamento'];        
        //TODO $data['finoAl'] = new DateTime($data['finoAl']);
    }

    protected function _complete(&$data) {
        $mp = new ModalitaPagamento($this->conn);
        $data['modalitaPagamento'] = $mp->get($data['idModalitaPagamento'], true);
        unset($data['idModalitaPagamento']);
    }
    
    protected function _isCoherent($data) {
        if (!isset($data['idGara']) ||
                !isset($data['idModalitaPagamento']) ||
                !isset($data['finoAl'])
        ) {
            return false;
        }
        if (!is_integer($data['idGara'])) {
            return false;
        }

        if (!is_integer($data['idModalitaPagamento'])) {
            return false;
        }
        
        if (!$this->_is_date($data['finoAl'])) {
            return false;
        }
        
        return true;
    }
}
