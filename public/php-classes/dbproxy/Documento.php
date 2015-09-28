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
                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 71);
            }
        } else {
            throw new ClientRequestException('view requested', 70);
        }
    }

    protected function _isCoherent($data, $view) {
        if (!isset($data['nomeFile'])) {
            return 'nomeFile is missing';
        }
        if (!isset($data['idRichiestaTesseramento'])) {
            return 'idRichiestaTesseramento is missing';
        }
        
        if(!$this->_is_string_with_length($data['nomeFile'])) {
            return 'nomeFile is a 0-length string';
        }
        if (!is_integer($data['idRichiestaTesseramento'])) {
            return 'idRichiestaTesseramento is not integer';
        }
        
        if(isset($view)) {
            switch($view) {
                case 'ordine':
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 60);
            }
        }
        
        return true;
    }
    
    protected function _removeUnsecureFields(&$data) {
        
    }

    public function add($data, $view) {
        $check = $this->_isCoherent($data, $view);
        if ($check !== true) {
            throw new ClientRequestException('Incoherent data for ' . get_class($this) . ". $check.", 93);
        }
        
        $r = $this->_baseAdd($data);
        
        if (isset($view)) {
            switch ($view) {
                case 'ordine':
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 50);
            }
        }
                
        $r = array_merge($data, $r);
        return $r;
    }

}
