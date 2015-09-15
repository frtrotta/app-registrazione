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
        //XXX $data['ricevutoIl'] = new DateTime($data['ricevutoIl']);
        $data['totale'] = (float) $data['totale'];
        $data['pagato'] = (boolean) $data['pagato'];        
        $data['ricevutaInviata'] = (boolean) $data['ricevutaInviata'];
        //XXX $data['ricevutaInviataIl'] = new DateTime($data['ricevutaInviataIl']);
        $data['idCliente'] = (int) $data['idCliente'];
        $data['idModalitaPagamento'] = (int) $data['idModalitaPagamento'];
    }
    
    

    protected function _complete(&$data) {
        
        $mp = new ModalitaPagamento($this->conn);
        $data['modalitaPagamento'] = $mp->get($data['idModalitaPagamento'], true);
        unset($data['idModalitaPagamento']);
        
        $u = new Utente($this->conn);
        $data['cliente'] = $u->get($data['idCliente'], true);
        unset($data['idCliente']);
        
        if (isset($view)) {
            switch ($view) {
                case 'ordine':
                    $i = new Iscrizione($this->conn);
                    $selectionClause = ['idOrdine' => $data['id']];
                    $data['iscrizioni'] = $i->getSelected($selectionClause, $view);
                case 'invito':
                case 'iscrizione':
                case 'default':
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
        if (!is_integer_optional($data['id'])) {
            return false;
        }

        if (!is_float($data['totale'])) {
            return false;
        }
        
        if (!$this->_is_datetime($data['ricevutoIl'])) {
            return false;
        }
        
        if (!is_bool($data['pagato'])) {
            return false;
        }
        
        if (!is_bool($data['ricevutaInviata'])) {
            return false;
        }
        
        if (!$this->_is_datetime_optional(@$data['ricevutaInviataIl'])) {
            return false;
        }
        
        if(!$this->_is_string_with_length($data['indirizzoCap'])) {
            return false;
        }
        
        if(!$this->_is_string_with_length($data['indirizzoCitta'])) {
            return false;
        }
        
        if(!$this->_is_string_with_length($data['indirizzoPaese'])) {
            return false;
        }
        
        return true;
    }
    
    protected function _removeUnsecureFields(&$data) {
        
    }
}
