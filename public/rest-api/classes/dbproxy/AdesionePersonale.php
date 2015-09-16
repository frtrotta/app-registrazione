<?php

namespace dbproxy;

class AdesionePersonale extends MysqlProxyBase {

    public function __construct(&$connection) {
        parent::__construct($connection, 'adesione_personale', ['id',
            'indirizzoCap',
            'indirizzoCitta',
            'indirizzoPaese',
            'categoriaFitri',
            'idUtente']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];
        $data['idUtente'] = (int) $data['idUtente'];
    }

    protected function _complete(&$data, $view) {
        if (isset($view)) {
            switch ($view) {
                case 'invito':
                case 'iscrizione':
                case 'ordine':
                    $u = new Utente($this->conn);
                    $data['utente'] = $u->get($data['idUtente'], $view);
                    unset($data['idUtente']);

                    $cf = new CategoriaFitri($this->conn);
                    $data['categoriaFitri'] = $u->get($data['categoriaFitri'], $view);
                    
                    $rt = new RichiestaTesseramento($this->conn);
                    $selectionClause = ['idAdesionePersonale' => $data['id']];
                    $data['richiestaTesseramento'] = $rt->getSelected($selectionClause, $view)[0];
                    break;
                case 'default':
                    $u = new Utente($this->conn);
                    $data['utente'] = $u->get($data['idUtente'], $view);
                    unset($data['idUtente']);

                    $cf = new CategoriaFitri($this->conn);
                    $data['categoriaFitri'] = $u->get($data['categoriaFitri'], $view);

                    $s = new Squadra($this->conn);
                    $temp = $this->_getOptionalChildIds('idAdesionePersonale', $data[$this->fieldList[0]], 'idSquadra', 'adesione_personale__squadra');
                    $n = count($temp);
                    switch ($n) {
                        case 0:
                            break;
                        case 1:
                            $data['squadra'] = $s->get($temp[0], true);
                            break;
                        default:
                            throw new MysqlProxyBaseException("Unexpected child number ($n)", 30);
                    }
                    break;
                default:
                    throw new ClientRequestException('Unsupported view: ' . $view, 71);
            }
        } else {
            throw new ClientRequestException('view requested', 70);
        }
    }

    protected function _isCoherent($data, $view) {
        if (
                !isset($data['categoriaFitri']) ||
                !isset($data['indirizzoCap']) ||
                !isset($data['indirizzoCitta']) ||
                !isset($data['indirizzoPaese']) ||
                !isset($data['idUtente'])
        ) {
            return false;
        }
        if (!$this->is_integer_optional($data['id'])) {
            return false;
        }

        if (!$this->_is_string_with_length($data['indirizzoCap'])) {
            return false;
        }

        if (!$this->_is_string_with_length($data['indirizzoCitta'])) {
            return false;
        }

        if (!$this->_is_string_with_length($data['indirizzoPaese'])) {
            return false;
        }

        if (!is_integer($data['idUtente'])) {
            return false;
        }
        
        if(isset($view)) {
            switch($view) {
                default:
                    throw new ClientRequestException('Unsupported view: ' . $view, 60);
            }
        }

        return true;
    }

    protected function _removeUnsecureFields(&$data) {
        
    }

}
