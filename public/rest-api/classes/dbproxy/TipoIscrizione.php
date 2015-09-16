<?php

namespace dbproxy;

class TipoIScrizione extends MysqlProxyBase {
    public function __construct(&$connection) {
        parent::__construct($connection, 'tipo_iscrizione', ['id',
            'nome_it',
            'descrizione_it',
            'nome_en',
            'descrizione_en']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];  
    }
    
    protected function _complete(&$data, $view) {        
        if (isset($view)) {
            switch ($view) {
                case 'default':
                    break;
                default:
                    throw new ClientRequestException('Unsupported view: ' . $view, 71);
            }
        } else {
            throw new ClientRequestException('view requested', 70);
        }
    }
    
    protected function _isCoherent($data) {
        if (
                !isset($data['nome_it']) ||
                !isset($data['nome_en'])
        ) {
            return false;
        }
        if (!is_integer_optional($data['id'])) {
            return false;
        }        
        
        if(!$this->_is_string_with_length($data['nome_it'])) {
            return false;
        }
        
        if(!$this->_is_string_with_length($data['nome_en'])) {
            return false;
        }
        
        if(!$this->_is_string_with_length_optional(@$data['descrizione_it'])) {
            return false;
        }
        
        if(!$this->_is_string_with_length_optional(@$data['descrizione_it'])) {
            return false;
        }

        return true;
    }
    
    protected function _removeUnsecureFields(&$data) {
        
    }
}
