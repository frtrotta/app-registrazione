<?php

namespace dbproxy;

class Documento extends MysqlProxyBase {
    public function __construct(&$connection) {
        parent::__construct($connection, 'documento', ['nomeFile',
            'idRichiestaTesseramento']);
    }

    protected function _castData(&$data) {
        $data['idRichiestaTesseramento'] = (int) $data['idRichiestaTesseramento'];
    }
    
    protected function _complete(&$data, $view) {
        if (isset($view)) {
            switch ($view) {
                case 'invito':
                    break;
                case 'default':
        $rt = new RichiestaTesseramento($this->conn);
        $data['richiestaDiTesseramento'] = $rt->get($data['idRichiestaTesseramento'], $view);
        unset($data['idRichiestaTesseramento']);
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . getclass($this) . ': ' . $view, 71);
            }
        } else {
            throw new ClientRequestException('view requested', 70);
        }
    }

    protected function _isCoherent($data, $view) {
        if (!isset($data['nomeFile']) ||
                !isset($data['idRichiestaTesseramento'])
        ) {
            return false;
        }        
        
        if(!$this->_is_string_with_length($data['nomeFile'])) {
            return false;
        }
        if (!is_integer($data['idRichiestaTesseramento'])) {
            return false;
        }
        
        if(isset($view)) {
            switch($view) {
                case 'ordine':
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . getclass($this) . ': ' . $view, 60);
            }
        }
        
        return true;
    }
    
    protected function _removeUnsecureFields(&$data) {
        
    }

    public function add(&$data, $view) {
        throw new \Exception('pippo');
        if (!$this->_isCoherent($data, $view)) {
            throw new ClientRequestException('Incoherent data for ' . getclasse($this) . '. The data you provided did not meet expectations: please check and try again.', 93);
        }
        
        $r = $this->_baseAdd($data);
        $r = array_merge($data, $r);
        
        if (isset($view)) {
            switch ($view) {
                case 'ordine':
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . getclass($this) . ': ' . $view, 50);
            }
        }
        return $r;
    }

}
