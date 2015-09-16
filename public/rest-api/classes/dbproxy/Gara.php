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
        //XXX $data['disputataIl'] = new DateTime($data['disputataIl']);
    }

    protected function _complete(&$data, $view) {
        if (isset($view)) {
            $idFieldName = 'idGara';
            $pars = [
                $idFieldName => $data['id'],
                'sort' => ['finoAl' => 1]
            ];

            switch ($view) {
                case 'default':

                    $amp = new AbilitazioneModalitaPagamento($this->conn);
                    $parsAMP = $pars;
                    $parsAMP['sort'] = array_merge(['idModalitaPagamento' => 1], $parsAMP['sort']);
                    $data['abilitazioneModalitaPagamento'] = $amp->getSelected($parsAMP, $view);
                    $this->_unsetField($data['abilitazioneModalitaPagamento'], $idFieldName);

                    $ati = new AbilitazioneTipoIscrizione($this->conn);
                    $parsATI = $pars;
                    $parsATI['sort'] = array_merge(['idTipoIscrizione' => 1], $parsATI['sort']);
                    $data['abilitazioneTipoIscrizione'] = $ati->getSelected($parsATI, $view);
                    $this->_unsetField($data['abilitazioneTipoIscrizione'], $idFieldName);

                    $atrt = new AbilitazioneTipoRichiestaTesseramento($this->conn);
                    $parsATRT = $pars;
                    $parsATRT['sort'] = array_merge(['idTipoRichiestaTesseramento' => 1], $parsATRT['sort']);
                    $data['abilitazioneTipoRichiestaTesseramento'] = $atrt->getSelected($parsATRT, $view);
                    $this->_unsetField($data['abilitazioneTipoRichiestaTesseramento'], $idFieldName);
                case 'invito':
                case 'iscrizione':

                    $tg = new TipoGara($this->conn);
                    $data['tipo'] = $tg->get($data['idTipoGara'], $view);
                    unset($data['idTipoGara']);

                case 'ordine':
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
                !isset($data['nome']) ||
                !isset($data['disputataIl']) ||
                !isset($data['idTipoGara'])
        ) {
            return false;
        }
        if (!$this->is_integer_optional($data['id'])) {
            return false;
        }

        if (!$this->_is_string_with_length($data['nome'])) {
            return false;
        }

        if (!$this->_is_string_with_length_optional(@$data['descrizione_it'])) {
            return false;
        }

        if (!$this->_is_string_with_length_optional(@$data['descrizione_it'])) {
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
        
        if(isset($view)) {
            switch($view) {
                default:
                    throw new ClientRequestException('Unsupported view for ' . getclass($this) . ': ' . $view, 60);
            }
        }

        return true;
    }

    protected function _removeUnsecureFields(&$data) {
        
    }

}
