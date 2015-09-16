<?php

namespace dbproxy;

class Risultato extends MysqlProxyBase {

    public function __construct(&$connection) {
        parent::__construct($connection, 'risultato', ['id',
            'codiceConclusioneGara',
            'posizione',
            'posizioneInSesso',
            'posizioneInCategoria',
            'tempo',
            'posizioneDopoNuoto',
            'tempoDopoNuoto',
            'posizioneDopoBici',
            'tempoDopoBici',
            'posizioneFrazioneBici',
            'tempoFrazioneBici',
            'posizioneFrazioneCorsa',
            'tempoFrazioneCorsa',
            'idIscrizione']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];
        $data['idUtente'] = (int) $data['idUtente'];
    }

    protected function _complete(&$data, $view) {
        $ccg = new CodiceConclusioneGara($this->conn);
        $data['conclusione'] = $ccg->get($data['codiceConclusioneGara'], $view);
        
        unset($data['codiceConclusioneGara']);
        if (isset($view)) {
            switch ($view) {
                case 'default':
                    $i = new Iscrizione($this->conn);
                    $data['iscrizione'] = $i->get($data['idIscrizione'], $view);
                    unset($data['idIscrizione']);
                case 'iscrizione':
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
                !isset($data['idIscrizione']) ||
                !isset($data['codiceConclusioneGara'])
        ) {
            return false;
        }
        if (!$this->is_integer_optional($data['id'])) {
            return false;
        }

        if (!is_integer($data['idIscrizione'])) {
            return false;
        }

        //--- opzionali
        if (!$this->_is_integer_optional(@$data['posizione'])) {
            return false;
        }
        if (!$this->_is_integer_optional(@$data['posizioneInSesso'])) {
            return false;
        }
        if (!$this->_is_integer_optional(@$data['posizioneDopoNuoto'])) {
            return false;
        }
        if (!$this->_is_integer_optional(@$data['posizioneDopoBici'])) {
            return false;
        }
        if (!$this->_is_integer_optional(@$data['posizioneFrazioneBici'])) {
            return false;
        }
        if (!$this->_is_integer_optional(@$data['posizioneFrazioneCorsa'])) {
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
