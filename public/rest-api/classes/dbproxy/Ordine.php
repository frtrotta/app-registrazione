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
        if (isset($view)) {
            switch ($view) {
                case 'ordine':
                    $i = new Iscrizione($this->conn);
                    $selectionClause = ['idOrdine' => $data['id']];
                    $data['iscrizioni'] = $i->getSelected($selectionClause, $view);
                    break;
                case 'invito':
                case 'iscrizione':
                case 'default':
                    $mp = new ModalitaPagamento($this->conn);
                    $data['modalitaPagamento'] = $mp->get($data['idModalitaPagamento'], true);
                    unset($data['idModalitaPagamento']);

                    $u = new Utente($this->conn);
                    $data['cliente'] = $u->get($data['idCliente'], true);
                    unset($data['idCliente']);
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 71);
            }
        } else {
            throw new ClientRequestException('view requested', 70);
        }
    }

    protected function _isCoherent($data, $view) {
        if (!isset($data['ricevutoIl'])) {
            return 'ricevutoIl is missing';
        }
        if (!isset($data['totale'])) {
            return 'totale is missing';
        }
        if (!isset($data['pagato'])) {
            return 'pagato is missing';
        }
        if (!isset($data['ricevutaInviata'])) {
            return 'ricevutaInviata is missing';
        }
        if (!isset($data['clienteIndirizzoCap'])) {
            return 'clienteIndirizzoCap is missing';
        }
        if (!isset($data['clienteIndirizzoCitta'])) {
            return 'clienteIndirizzoCitta is missing';
        }
        if (!isset($data['clienteIndirizzoPaese'])) {
            return 'clienteIndirizzoPaese is missing';
        }
        if (!isset($data['idModalitaPagamento'])) {
            return 'idModalitaPagamento is missing';
        }
        if (!isset($data['idCliente'])) {
            return 'idCliente is missing';
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

        // TODO pagato solo false?
        if (!is_bool($data['pagato'])) {
            return 'pagato is not boolean';
        }

        if (!is_bool($data['ricevutaInviata'])) {
            return 'ricevutaInviata is not boolean';
        }

        if (!$this->_is_datetime_optional(@$data['ricevutaInviataIl'])) {
            return 'ricevutaInviataIl is set but it is not a valid datetime';
        }

        if (!$this->_is_string_with_length($data['clienteIndirizzoCap'])) {
            return 'indirizzoCap is a 0-length string';
        }

        if (!$this->_is_string_with_length($data['clienteIndirizzoCitta'])) {
            return 'indirizzoCitta is a 0òlength string';
        }

        if (!$this->_is_string_with_length($data['clienteIndirizzoPaese'])) {
            return 'indirizzoPaese is a 0-length string';
        }

        if (!is_integer($data['idModalitaPagamento'])) {
            return 'idModalitaPagamento is not integer';
        }

        if (!is_integer($data['idCliente'])) {
            return 'idCliente is not integer';
        }

        if (isset($view)) {
            switch ($view) {
                case 'ordine':
                    if (!isset($data['iscrizioni'])) {
                        return 'iscrizioni is not set';
                    }
                    if (!is_array($data['iscrizioni'])) {
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

    public function add($data, $view) {
        $check = $this->_isCoherent($data, $view);
        if ($check !== true) {
            throw new ClientRequestException('Incoherent data for ' . get_class($this) . ". $check.", 91);
        }

        $r = $this->_baseAdd($data);

        if (isset($view)) {
            switch ($view) {
                case 'ordine':
                    $iProxy = new Iscrizione($this->conn);
                    foreach ($data['iscrizioni'] as &$i) {
                        $i['idOrdine'] = $r[$this->fieldList[0]];
                        $ir = $iProxy->add($i, $view);
                    }
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 50);
            }
        }

        return $this->get($r[$this->fieldList[0]], $view);
    }

}
