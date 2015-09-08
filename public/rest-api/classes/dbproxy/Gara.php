<?php

namespace dbproxy;

class Gara extends MysqlProxyBase {
    public function __construct($connection) {
        parent::__construct($connection, 'gara', ['id',
            'nome',
            'descrizione_it',
            'descrizione_en',
            'disputataIl',
            'idTipoGara']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];
        $data['idTipoGara'] = (int) $data['idTipoGara'];
        //TODO $data['disputataIl'] = new DateTime($data['disputataIl']);
    }

    protected function _complete(&$data) {
        $tg = new TipoGara($this->conn);
        $data['tipo'] = $tg->get($data['idTipoGara']);
        unset($data['idTipoGara']);
        
        $idFieldName = 'idGara';
        $pars = [$idFieldName => $data['id']];
        
        
        $amp = new AbilitazioneModalitaPagamento($this->conn);
        $data['abilitazioneModalitaPagamento'] = $amp->getSelected($pars, true);
        $this->_unsetField($data['abilitazioneModalitaPagamento'], $idFieldName);
        
        $ati = new AbilitazioneTipoIscrizione($this->conn);
        $data['abilitazioneTipoIscrizione'] = $ati->getSelected($pars, true);
        $this->_unsetField($data['abilitazioneTipoIscrizione'], $idFieldName);
        
        $atrt = new AbilitazioneTipoRichiestaTesseramento($this->conn);
        $data['abilitazioneTipoRichiestaTesseramento'] = $atrt->getSelected($pars, true);
        $this->_unsetField($data['abilitazioneTipoRichiestaTesseramento'], $idFieldName);
    }
    
    protected function _isCoherent($data) {
        if (!isset($data['id']) ||
                !isset($data['nome']) ||
                !isset($data['disputataIl']) ||
                !isset($data['idTipoGara'])
        ) {
            return false;
        }
        if (!is_integer($data['id'])) {
            return false;
        }

        if (!is_integer($data['idTipoGara'])) {
            return false;
        }
        
        if (!$this->_is_date($data['disputataIl'])) {
            return false;
        }
        
        return true;
    }
}
