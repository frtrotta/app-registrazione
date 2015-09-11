<?php

namespace dbproxy;

class Gara extends MysqlProxyBase {
    public function __construct(&$connection) {
        parent::__construct($connection, 'gara', ['id',
            'nome',
            'descrizione_it',
            'descrizione_en',
            'disputataIl',
            'iscrizioneModificabileFinoAl',
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
        $pars = [
            $idFieldName => $data['id'],
            'sort' => ['finoAl' => 1]
            ];
        
        $amp = new AbilitazioneModalitaPagamento($this->conn);
        $parsAMP = $pars;
        $parsAMP['sort'] = array_merge(['idModalitaPagamento' => 1], $parsAMP['sort']);
        $data['abilitazioneModalitaPagamento'] = $amp->getSelected($parsAMP, true);
        $this->_unsetField($data['abilitazioneModalitaPagamento'], $idFieldName);
        
        $ati = new AbilitazioneTipoIscrizione($this->conn);
        $parsATI = $pars;
        $parsATI['sort'] = array_merge(['idTipoIscrizione' => 1], $parsATI['sort']);
        $data['abilitazioneTipoIscrizione'] = $ati->getSelected($parsATI, true);
        $this->_unsetField($data['abilitazioneTipoIscrizione'], $idFieldName);
        
        $atrt = new AbilitazioneTipoRichiestaTesseramento($this->conn);
        $parsATRT = $pars;
        $parsATRT['sort'] = array_merge(['idTipoRichiestaTesseramento' => 1], $parsATRT['sort']);
        $data['abilitazioneTipoRichiestaTesseramento'] = $atrt->getSelected($parsATRT, true);
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
        
        if (!$this->_is_datetime($data['disputataIl'])) {
            return false;
        }  
        
        if (!$this->_is_datetime($data['iscrizioneModificabileFinoAl'])) {
            return false;
        }
        
        return true;
    }
    
    public function removeUnsecureFields(&$data) {
        
    }
}
