<?php

namespace dbproxy;

class Tesseramento extends MysqlProxyBase {
    public function __construct(&$connection) {
        parent::__construct($connection, 'tesseramento', ['id',
            'finoAl',
            'matricola',
            'stranieroSocieta',
            'stranieroStato',
            'idTipoTesseramento']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];
        //XXX $data['finoAl'] = new DateTime($data['finoAl']);
        $data['idTipoTesseramento'] = (int) $data['idTipoTesseramento'];      
    }

    
    protected function _complete(&$data, $view) {
        if (isset($view)) {
            switch ($view) {
                case 'iscrizione':
                case 'ordine':
                case 'default':
                    break;
                default:
                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 71);
            }
        } else {
            throw new ClientRequestException('view requested', 70);
        }
        
    }

    protected function _isCoherent($data, $view) {
        if (!isset($data['finoAl'])) {
            return 'finoAl is missing';
        }
        if (!isset($data['idTipoTesseramento'])) {
            return 'idTipoTesseramento is missing';
        }
        
        if (!$this->_is_integer_optional(@$data['id'])) {
            return 'id is set but it is not integer';
        }
        
        if (!$this->_is_datetime($data['finoAl'])) {
            return 'finoAl is not a valid datetime';
        }
        
        if (!is_integer($data['idTipoTesseramento'])) {
            return 'idTipoTesseramento is not integer';
        }
        
        if (!$this->_is_integer_optional(@$data['codiceSocietaFitri'])) {
            return 'codiceSocietaFitri is set but it is not integer';
        }
        
        if(!$this->_is_string_with_length_optional(@$data['matricola'])) {
            return 'matricola is a 0-length string';
        }
        
        if(!$this->_is_string_with_length_optional(@$data['stranieroSocieta'])) {
            return 'stranieroSocieta is a 0-length string';
        }
        
        if(!$this->_is_string_with_length_optional(@$data['stranieroStato'])) {
            return 'stranieroStato is a 0-length string';
        }
        
        // combinazioni
        if(
                !(is_integer(@$data['codiceSocietaFitri']) &&
                $this->_is_string_with_length(@$data['matricola']))
                
                &&
                
                !($this->_is_string_with_length(@$data['matricola']) &&
                $this->_is_string_with_length(@$data['stranieroSocieta']) &&
                $this->_is_string_with_length(@$data['stranieroStato']))
                
                &&
                
                !(isset($data['matricola']) &&
                isset($data['stranieroSocieta']) &&
                isset($data['stranieroStato']))
                ) {
            return 'The combination of matricola, codiceSocietaFitri, stranieroSocieta and/or stranieroStato is not valid';
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
