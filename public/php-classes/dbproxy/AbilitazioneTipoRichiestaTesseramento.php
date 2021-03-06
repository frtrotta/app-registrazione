<?php

namespace dbproxy;

class AbilitazioneTipoRichiestaTesseramento extends MysqlProxyBase {

    public function __construct(&$connection) {
        parent::__construct($connection, 'abilitazione_tipo_richiesta_tesseramento', ['idGara',
            'idTipoRichiestaTesseramento',
            'finoAl',
            'costo']);
    }

    protected function _castData(&$data) {
        $data['idGara'] = (int) $data['idGara'];
        $data['idTipoRichiestaTesseramento'] = (int) $data['idTipoRichiestaTesseramento'];
        $data['costo'] = (float) $data['costo'];
        //XXX $data['finoAl'] = new DateTime($data['finoAl']);
    }

    protected function _complete(&$data, $view) {
        if (isset($view)) {
            switch ($view) {
                case 'default':
                    $ti = new TipoRichiestaTesseramento($this->conn);
                    $data['tipoRichiestaTesseramento'] = $ti->get($data['idTipoRichiestaTesseramento'], $view);
                    unset($data['idTipoRichiestaTesseramento']);
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 71);
            }
        } else {
            throw new ClientRequestException('view requested', 70);
        }
    }

//    protected function _isCoherent($data, $view) {
//        if (!isset($data['idTipoRichiestaTesseramento']) ||
//                !isset($data['idGara']) ||
//                !isset($data['finoAl']) ||
//                !isset($data['costo'])
//        ) {
//            return false;
//        }
//        if (!is_integer($data['idTipoRichiestaTesseramento'])) {
//            return false;
//        }
//
//        if (!is_integer($data['idGara'])) {
//            return false;
//        }
//
//        if (!$this->_is_datetime($data['finoAl'])) {
//            return false;
//        }
//
//        if (!is_float($data['costo'])) {
//            return false;
//        }
//        
//        if(isset($view)) {
//            switch($view) {
//                default:
//                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 60);
//            }
//        }
//
//        return true;
//    }

    protected function _removeUnsecureFields(&$data) {
        
    }

}
