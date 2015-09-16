<?php

namespace dbproxy;

class AbilitazioneModalitaPagamento extends MysqlProxyBase {

    public function __construct(&$connection) {
        parent::__construct($connection, 'abilitazione_modalita_pagamento', ['idGara',
            'idModalitaPagamento',
            'finoAl']);
    }

    protected function _castData(&$data) {
        $data['idGara'] = (int) $data['idGara'];
        $data['idModalitaPagamento'] = (int) $data['idModalitaPagamento'];
        //XXX $data['finoAl'] = new DateTime($data['finoAl']);
    }

    protected function _complete(&$data, $view) {
        if (isset($view)) {
            switch ($view) {
                case 'default':
                    $mp = new ModalitaPagamento($this->conn);
                    $data['modalitaPagamento'] = $mp->get($data['idModalitaPagamento'], $view);
                    unset($data['idModalitaPagamento']);
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 71);
            }
        } else {
            throw new ClientRequestException('view requested', 70);
        }
    }

//    protected function _isCoherent($data, $view) {
//        if (!isset($data['idGara']) ||
//                !isset($data['idModalitaPagamento']) ||
//                !isset($data['finoAl'])
//        ) {
//            return false;
//        }
//        if (!is_integer($data['idGara'])) {
//            return false;
//        }
//
//        if (!is_integer($data['idModalitaPagamento'])) {
//            return false;
//        }
//
//        if (!$this->_is_datetime($data['finoAl'])) {
//            return false;
//        }
//        
//        if(isset($view)) {
//            switch($view) {
//                default:
//                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 60);
//            }
//        }
//
//        return true;
//    }

    protected function _removeUnsecureFields(&$data) {
        
    }

}
