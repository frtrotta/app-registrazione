<?php

namespace dbproxy;

class RichiestaTesseramento extends MysqlProxyBase {

    public function __construct(&$connection) {
        parent::__construct($connection, 'richiesta_tesseramento', ['id',
            'eseguitaIl',
            'verificata',
            'idTipoRichiestaTesseramento',
            'idAdesionePersonale']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];
        //XXX $data['eseguitaIl'] = new DateTime($data['eseguitaIl']);
        $data['verificata'] = (boolean) $data['verificata'];
        $data['idTipoRichiestaTesseramento'] = (int) $data['idTipoRichiestaTesseramento'];
        $data['idAdesionePersonale'] = (int) $data['idAdesionePersonale'];
    }

    protected function _complete(&$data, $view) {
        if (isset($view)) {
            switch ($view) {
                case 'iscrizione':
                    $d = new Documento($this->conn);
                    $selectionClause = ['idRichiestaTesseramento' => $data['id']];
                    $data['documenti'] = $d->getSelected($selectionClause, $view);
                case 'ordine':
                    $trt = new TipoRichiestaTesseramento($this->conn);
                    $data['tipoRichiestaTesseramento'] = $trt->get($data['idTipoRichiestaTesseramento'], true);
                    unset($data['idTipoRichiestaTesseramento']);
                    break;
                case 'default':
                    $ap = new AdesionePersonale($this->conn);
                    $data['adesionePersonale'] = $ap->get($data['idAdesionePersonale'], true);
                    unset($data['idAdesionePersonale']);
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 71);
            }
        } else {
            throw new ClientRequestException('view requested', 70);
        }
    }

    protected function _isCoherent($data, $view) {
        if (!isset($data['eseguitaIl'])) {
            return 'eseguitaIl is missing';
        }
        if (!isset($data['verificata'])) {
            return 'verificata is missing';
        }
        if (!isset($data['idTipoRichiestaTesseramento'])) {
            return 'idTipoRichiestaTesseramento is missing';
        }
        
        if (!$this->_is_integer_optional(@$data['id'])) {
            return 'is is set but it is not integer';
        }

        if (!$this->_is_datetime($data['eseguitaIl'])) {
            return 'eseguitaIl is not a valid datetime';
        }

        if (!is_bool($data['verificata'])) {
            return 'verificata is not boolean';
        }

        if (!is_integer($data['idTipoRichiestaTesseramento'])) {
            return 'idTipoRichiestaTesseramento is not integer';
        }

        if (isset($view)) {
            switch ($view) {
                case 'ordine':
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
                    if (isset($data['tesseramento'])) {
                        $tProxy = new Tesseramento($this->conn);
                        $data['tesseramento']['idRichiestaTesseramento'] = $r['id'];
                        $rt = $tProxy->add($data['tesseramento'], $view);
                        $data['tesseramento'] = array_merge($data['tesseramento'], $t);
                    }
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 50);
            }
        }
                
        $r = array_merge($data, $r);
        return $r;
    }

}
