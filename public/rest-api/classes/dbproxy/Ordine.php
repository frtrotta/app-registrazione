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

    protected function _complete(&$data, $view) {

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
                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 71);
            }
        } else {
            throw new ClientRequestException('view requested', 70);
        }
    }

    protected function _isCoherent($data, $view) {
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
            return 'At least one required field is not set';
        }
        if (!$this->_is_integer_optional(@$data['id'])) {
            return 'id is set but it is not integer';
        }

        if (!is_float($data['totale'])) {
            return 'totale is not float';
        }

        if (!$this->_is_datetime($data['ricevutoIl'])) {
            return 'ricevutoIl is not a valid datetime';
        }

        if (!is_bool($data['pagato'])) {
            return 'pagato is not boolean';
        }

        if (!is_bool($data['ricevutaInviata'])) {
            return 'ricevutaInviata is not boolean';
        }

        if (!$this->_is_datetime_optional(@$data['ricevutaInviataIl'])) {
            return 'ricevutaInviataIl is set but it is not a valid datetime';
        }

        if (!$this->_is_string_with_length($data['indirizzoCap'])) {
            return 'indirizzoCap is a 0-length string';
        }

        if (!$this->_is_string_with_length($data['indirizzoCitta'])) {
            return 'indirizzoCitta is a 0òlength string';
        }

        if (!$this->_is_string_with_length($data['indirizzoPaese'])) {
            return 'indirizzoPaese is a 0-length string';
        }

        if (!is_integer($data['idModalitaPagamento'])) {
            return 'idModalitaPagamento is not integer';
        }

        if (!is_integer($data['idModalitaCliente'])) {
            return 'idModalitaCliente is not integer';
        }

        if (isset($view)) {
            switch ($view) {
                case 'ordine':
                    if (!isset($data['iscrizioni'])) {
                        return 'iscrizioni is not set';
                    }
                    if (is_array($data['iscrizioni'])) {
                        return 'iscrizioni is not an array';
                    }
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 60);
            }
        }

        return true;
    }

    protected function _removeUnsecureFields(&$data) {
        
    }

    public function add(&$data, $view) {
        if (!$this->_isCoherent($data, $view)) {
            throw new ClientRequestException('Incoherent data for ' . get_class($this) . '. The data you provided did not meet expectations: please check and try again.', 93);
        }
        
        $r = $this->_baseAdd($data);
        $r = array_merge($data, $r);
        
        if (isset($view)) {
            switch ($view) {
                case 'ordine':
                    $iProxy = new Iscrizione($this->conn);
                    foreach ($data['iscrizioni'] as &$i) {
                        $i['idOrdine'] = $data['id'];
                        $ir = $iProxy->add($i, $view);
                        $i = array_merge($i, $ir);
                    }
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 50);
            }
        }
        return $r;
    }

}
