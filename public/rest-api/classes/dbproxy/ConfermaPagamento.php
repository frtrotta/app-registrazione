<?php

namespace dbproxy;

class ConfermaPagamento extends MysqlProxyBase {

    public function __construct(&$connection) {
        parent::__construct($connection, 'conferma_pagamento', ['id',
            'idOrdine',
            'eseguitaIl',
            'idAmministratore']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];
        $data['idOrdine'] = (int) $data['idOrdine'];
        //TODO $data['eseguitaIl'] = new DateTime($data['eseguitaIl']);
        $data['idAmministratore'] = (int) $data['idAmministratore'];
    }

    protected function _complete(&$data, $view) {
        if (isset($view)) {
            switch ($view) {
                case 'default':
                    $o = new Ordine($this->conn);
                    $data['ordine'] = $o->get($data['idOrdine'], $view);
                    unset($data['idOrdine']);

                    $a = new Utente($this->conn);
                    $data['amministratore'] = $a->get($data['idAmministratore'], $view);
                    unset($data['idAmministratore']);
                    break;
                default:
                    throw new ClientRequestException('Unsupported view: ' . $view, 71);
            }
        } else {
            throw new ClientRequestException('view requested', 70);
        }
    }

    protected function _isCoherent($data) {
        if (
                !isset($data['idOrdine']) ||
                !isset($data['idAmministratore']) ||
                !isset($data['eseguitaIl'])
        ) {
            return false;
        }
        if (!is_integer_optinal($data['id'])) {
            return false;
        }

        if (!is_integer($data['idOrdine'])) {
            return false;
        }

        if (!is_integer($data['idAmministratore'])) {
            return false;
        }

        if (!$this->_is_datetime($data['eseguitaIl'])) {
            return false;
        }

        return true;
    }

    protected function _removeUnsecureFields(&$data) {
        
    }

}
