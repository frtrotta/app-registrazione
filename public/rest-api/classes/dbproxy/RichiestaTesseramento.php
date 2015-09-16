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
                    throw new ClientRequestException('Unsupported view: ' . $view, 71);
            }
        } else {
            throw new ClientRequestException('view requested', 70);
        }
    }

    protected function _isCoherent($data, $view) {
        if (
                !isset($data['eseguitaIl']) ||
                !isset($data['verificata'])
        ) {
            return false;
        }
        if (!is_integer_optional($data['id'])) {
            return false;
        }

        if (!$this->_is_datetime($data['eseguitaIl'])) {
            return false;
        }

        if (!is_bool($data['verificata'])) {
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
