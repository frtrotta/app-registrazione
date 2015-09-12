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

    protected function _complete(&$data) {
        $ti = new TipoIscrizione($this->conn);
        $data['tipoIscrizione'] = $ti->get($data['idTipoIscrizione']);
        unset($data['idTipoIscrizione']);
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
