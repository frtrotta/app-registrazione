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
                case 'iscrizione':
                case 'invito':
                    $u = new Utente($this->conn);
                    $data['utente'] = $u->get($data['idUtente'], $view);
                    unset($data['idUtente']);

                    $cf = new CategoriaFitri($this->conn);
                    $data['categoriaFitri'] = $u->get($data['categoriaFitri'], $view);
                case 'ordine':
                    $rt = new RichiestaTesseramento($this->conn);
                    $selectionClause = ['idAdesionePersonale' => $data['id']];
                    $data['richiestaTesseramento'] = $rt->getSelected($selectionClause, $view)[0];
                    unset($data['richiestaTesseramento']['idAdesionePersonale']);
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
                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 71);
            }
        } else {
            throw new ClientRequestException('view requested', 70);
        }
    }

    protected function _isCoherent($data, $view) {
        if (!isset($data['categoriaFitri'])) {
            return 'categoriaFitri is missing';
        }
        if (!isset($data['indirizzoCap'])) {
            return 'indirizzoCap is missing';
        }
        if (!isset($data['indirizzoCitta'])) {
            return 'indirizzoCitta is missing';
        }
        if (!isset($data['indirizzoPaese'])) {
            return 'indirizzoPaese is missing';
        }
        if (!isset($data['idUtente'])) {
            return 'idUtente is missing';
        }

        if (!$this->_is_integer_optional(@$data['id'])) {
            return 'id is set but it is not integer';
        }

        if (!$this->_is_string_with_length($data['categoriaFitri'])) {
            return 'categoriaFitri is a 0-length string';
        }

        if (!$this->_is_string_with_length($data['indirizzoCap'])) {
            return 'indirizzoCap is a 0-length string';
        }

        if (!$this->_is_string_with_length($data['indirizzoCitta'])) {
            return 'indirizzoCitta is a 0-length string';
        }

        if (!$this->_is_string_with_length($data['indirizzoPaese'])) {
            return 'indirizzoPaese is a 0-length string';
        }

        if (!is_integer($data['idUtente'])) {
            return 'idUtente is not integer';
        }

        if (isset($view)) {
            switch ($view) {
                case 'ordine':
                    if (!isset($data['richiestaTesseramento'])) {
                        return 'idRichiestaTesseramento is not set';
                    }

                    if (!$this->_is_integer_optional(@$data['idSquadra'])) {
                        return 'idSquadra is set but it is not integer';
                    }

                    if (!$this->_is_integer_optional(@$data['idIscrizione'])) {
                        return 'idIscrizione is set but it is not integer';
                    }

                    if ((isset($data['idSquadra']) && isset($data['idIscrizione'])) ||
                            (!isset($data['idSquadra']) && !isset($data['idIscrizione']))) {
                        return 'idSquadra and idIscrizione cannot both be set';
                    }

                    // Nessuna adesione personale può essere correlata ad un invito, durante l'ordine
                    if (isset($data['idInvito'])) {
                        return 'idInvito cannot be set';
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
            throw new ClientRequestException('Incoherent data for ' . get_class($this) . ". $check.", 93);
        }

        $r = $this->_baseAdd($data);

        if (isset($view)) {
            switch ($view) {
                case 'ordine':
                    $rtProxy = new RichiestaTesseramento($this->conn);
                    $data['richiestaTesseramento']['idAdesionePersonale'] = $r[$this->fieldList[0]];
                    $rtProxy->add($data['richiestaTesseramento'], $view);

                    if (isset($data['idIscrizione'])) {
                        $this->_addOptionalRelation('idIscrizione', $data['idIscrizione'], 'idAdesionePersonale', $r[$this->fieldList[0]], 'iscrizione__adesione_personale');
                    }

                    if (isset($data['idSquadra'])) {
                        $this->_addOptionalRelation('idAdesionePersonale', $r[$this->fieldList[0]], 'idSquadra', $data['idSquadra'], 'adesione_personale__squadra');
                    }
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 50);
            }
        }

        return $this->get($this->fieldList[0], $view);
    }

}
