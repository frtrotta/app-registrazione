<?php

namespace dbproxy;

class Invito extends MysqlProxyBase {
    public function __construct(&$connection) {
        parent::__construct($connection, 'invito', ['codice',
            'nome',
            'cognome',
            'email',
            'idIscrizione']);
    }

    protected function _castData(&$data) {
        $data['idIscrizione'] = (int) $data['idIscrizione'];
    }

    protected function _complete(&$data) {
        $ap = new AdesionePersonale($this->conn);
        $temp = $this->_getOptionalChildIds('idInvito', $data[$this->fieldList[0]], 'idAdesionePersonale', 'adesione_personale__invito');
        $n = count($temp);
        switch($n) {
            case 0:
                break;
            case 1:
                $data['adesionePersonale'] = $s->get($temp[0], true);
                break;
            default:
                throw new MysqlProxyBaseException("Unespected child number ($n)", 30);
        }
    }

    protected function _isCoherent($data) {
        if (!isset($data['codice']) ||
                !isset($data['nome']) ||
                !isset($data['cognome']) ||
                !isset($data['email']) || // TODO opzionale?
                !isset($data['idIscrizione'])
        ) {
            return false;
        }
        if (!is_integer($data['idIscrizione'])) {
            return false;
        }
        
        return true;
    }
    
    public function removeUnsecureFields(&$data) {
        
    }
}
