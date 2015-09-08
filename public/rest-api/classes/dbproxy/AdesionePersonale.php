<?php

namespace dbproxy;

class AdesionePersonale extends MysqlProxyBase {

    public function __construct($connection) {
        parent::__construct($connection, 'adesione_personale', ['id',
            'indirizzoLinea1',
            'indirizzoLinea2',
            'indirizzoCap',
            'indirizzoCitta',
            'indirizzoProvincia',
            'indirizzoStato',
            'categoriaFitri',
            'idUtente']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];
        $data['idUtente'] = (int) $data['idUtente'];
    }

    protected function _complete(&$data) {
        $u = new Utente($this->conn);
        $data['utente'] = $u->get($data['idUtente'], true);
        unset($data['idUtente']);

        $cf = new CategoriaFitri($this->conn);
        $data['categoriaFitri'] = $u->get($data['categoriaFitri'], true);

        $s = new Squadra($this->conn);
        $temp = $this->_getOptionalChildIds('idAdesionePersonale', $data[$this->fieldList[0]], 'idSquadra', 'adesione_personale__squadra');
        $n = count($temp);
        switch($n) {
            case 0:
                break;
            case 1:
                $data['squadra'] = $s->get($temp[0], true);
                break;
            default:
                throw new MysqlProxyBaseException("Unespected child number ($n)", 30);
        }
    }

    protected function _isCoherent($data) {
        if (!isset($data['id']) ||
                !isset($data['categoriaFitri']) ||
                !isset($data['indirizzoLinea1']) ||
                !isset($data['indirizzoCap']) ||
                !isset($data['indirizzoCitta']) ||
                // TODO !isset($data['indirizzoProvincia']) ||
                !isset($data['indirizzoStato']) ||
                !isset($data['idUtente'])
        ) {
            return false;
        }
        if (!is_integer($data['id'])) {
            return false;
        }

        if (!is_integer($data['idUtente'])) {
            return false;
        }

        return true;
    }

}
