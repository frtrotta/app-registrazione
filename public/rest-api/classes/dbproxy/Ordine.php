<?php

namespace dbproxy;

class Ordine extends MysqlProxyBase {
    public function __construct(&$connection) {
        parent::__construct($connection, 'ordine', ['id',
            'ricevutoIl',
            'totale',
            'pagato',
            'ricevutaInviata',
            'ricevutaInviataIl',
            'note',
            'clienteIndirizzoCap',
            'clienteIndirizzoCitta',
            'clienteIndirizzoPaese', 
            'idCliente',
            'idModalitaPagamento']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];
        //TODO $data['ricevutoIl'] = new DateTime($data['ricevutoIl']);
        $data['totale'] = (float) $data['totale'];
        $data['pagato'] = (boolean) $data['pagato'];        
        $data['ricevutaInviata'] = (boolean) $data['ricevutaInviata'];
        //TODO $data['ricevutaInviataIl'] = new DateTime($data['ricevutaInviataIl']);
        $data['idCliente'] = (int) $data['idCliente'];
        $data['idModalitaPagamento'] = (int) $data['idModalitaPagamento'];
    }
    
    

    protected function _complete(&$data) {
        $u = new Utente($this->conn);
        $data['cliente'] = $u->get($data['idCliente'], true);
        unset($data['idCliente']);
        
        $mp = new ModalitaPagamento($this->conn);
        $data['modalitaPagamento'] = $mp->get($data['idModalitaPagamento'], true);
        unset($data['idModalitaPagamento']);
    }

    protected function _isCoherent($data) {
        if (!isset($data['id']) ||
                !isset($data['ricevutoIl']) ||
                !isset($data['totale']) ||
                !isset($data['pagato']) ||
                !isset($data['ricevutaInviata']) ||
                !isset($data['indirizzoCap']) ||
                !isset($data['indirizzoCitta']) ||
                !isset($data['indirizzoPaese']) ||
                !isset($data['idModalitaPagamento']) ||
                !isset($data['idCliente'])
        ) {
            return false;
        }
        if (!is_integer($data['id'])) {
            return false;
        }

        if (!is_float($data['totale'])) {
            return false;
        }
        
        if (!$this->_is_datetime($data['ricevutoIl'])) {
            return false;
        }
        
        if (!$this->is_bool($data['pagato'])) {
            return false;
        }
        
        if (!$this->is_bool($data['ricevutaInviata'])) {
            return false;
        }
        
        return true;
    }
}
