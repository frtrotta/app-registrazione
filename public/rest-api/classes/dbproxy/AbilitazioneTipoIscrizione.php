<?php

namespace dbproxy;

class AbilitazioneTipoIscrizione extends MysqlProxyBase {

    public function __construct(&$connection) {
        parent::__construct($connection, 'abilitazione_tipo_iscrizione', ['idGara',
            'idTipoIscrizione',
            'finoAl',
            'costo']);
    }

    protected function _castData(&$data) {
        $data['idGara'] = (int) $data['idGara'];
        $data['idTipoIscrizione'] = (int) $data['idTipoIscrizione'];
        $data['costo'] = (float) $data['costo'];
        //TODO $data['finoAl'] = new DateTime($data['finoAl']);
    }

    protected function _complete(&$data, $view) {
        if (isset($view)) {
            switch ($view) {
                case 'default':
                    $ti = new TipoIscrizione($this->conn);
                    $data['tipoIscrizione'] = $ti->get($data['idTipoIscrizione'], $view);
                    unset($data['idTipoIscrizione']);
                    break;
                default:
                    throw new ClientRequestException('Unsupported view: ' . $view, 71);
            }
        } else {
            throw new ClientRequestException('view requested', 70);
        }
    }

    protected function _isCoherent($data) {
        if (!isset($data['idGara']) ||
                !isset($data['idTipoIscrizione']) ||
                !isset($data['finoAl']) ||
                !isset($data['costo'])
        ) {
            return false;
        }
        if (!is_integer($data['idGara'])) {
            return false;
        }

        if (!is_integer($data['idTipoIscrizione'])) {
            return false;
        }

        if (!$this->_is_datetime($data['finoAl'])) {
            return false;
        }

        if (!is_float($data['costo'])) {
            return false;
        }

        return true;
    }

    protected function _removeUnsecureFields(&$data) {
        
    }

}
