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
                    $d = new Documento;
                    $selectionClause = ['idRichiestaTesseramento' => $data['id']];
                    $data['documenti'] = $d->getSelected($selectionClause, $view);
                case 'ordine':
                    $trt = new TipoRichiestaTesseramento($this->conn);
                    $data['tipoRichiestaTesseramento'] = $trt->get($data['idTipoRichiestaTesseramento'], true);
                    unset($data['idTipoRichiestaTesseramento']);
                case 'default':
                    $ap = new AdesionePersonale($this->conn);
                    $data['adesionePersonale'] = $ap->get($data['idAdesionePersonale'], true);
                    unset($data['idAdesionePersonale']);
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . getclass($this) . ': ' . $view, 71);
            }
        } else {
            throw new ClientRequestException('view requested', 70);
        }
    }

    protected function _isCoherent($data, $view) {
        if (
                !isset($data['eseguitaIl']) ||
                !isset($data['verificata']) ||
                !isset($data['idTipoRichiestaTesseramento'])
        ) {
            return false;
        }
        if (!$this->is_integer_optional($data['id'])) {
            return false;
        }

        if (!$this->_is_datetime($data['eseguitaIl'])) {
            return false;
        }

        if (!is_bool($data['verificata'])) {
            return false;
        }

        if (!is_integer($data['idTipoRichiestaTesseramento'])) {
            return false;
        }

        if (isset($view)) {
            switch ($view) {
                case 'ordine':
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . getclass($this) . ': ' . $view, 60);
            }
        }

        return true;
    }

    protected function _removeUnsecureFields(&$data) {
        
    }

    public function add(&$data, $view) {
        if (!$this->_isCoherent($data, $view)) {
            throw new ClientRequestException('Incoherent data for ' . getclasse($this) . '. The data you provided did not meet expectations: please check and try again.', 93);
        }

        $r = $this->_baseAdd($data);
        $r = array_merge($data, $r);

        if (isset($view)) {
            switch ($view) {
                case 'ordine':
                    if (isset($data['tesseramento'])) {
                        $tProxy = new Tesseramento($this->conn);
                        $data['tesseramento']['idRichiestaTesseramento'] = $data['id'];
                        $rt = $tProxy->add($data['tesseramento'], $view);
                        $data['tesseramento'] = array_merge($data['tesseramento'], $t);
                    }
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . getclass($this) . ': ' . $view, 50);
            }
        }
        return $r;
    }

}
