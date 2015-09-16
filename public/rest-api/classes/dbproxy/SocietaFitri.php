<?php

namespace dbproxy;

class SocietaFitri extends MysqlProxyBase {
    public function __construct(&$connection) {
        parent::__construct($connection, 'societa_fitri', ['codice',
            'nome', 'provincia', 'email']);
    }

    protected function _castData(&$data) {
        $data['codice'] = (int) $data['codice'];  
    }
    
    protected function _complete(&$data, $view) {
        
        if (isset($view)) {
            switch ($view) {
                case 'invito':
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

//    protected function _isCoherent($data, $view) {
//        if (!isset($data['codice']) ||
//                !isset($data['nome'])
//        ) {
//            return false;
//        }
//        if (!is_integer($data['codice'])) {
//            return false;
//        }
//        
//        if(!$this->_is_string_with_length($data['nome'])) {
//            return false;
//        }
//        
//        if(!$this->_is_string_with_length_optional(@$data['provincia'])) {
//            return false;
//        }
//        
//        if(!$this->_is_string_with_length_optional(@$data['email'])) {
//            return false;
//        }
//        
//        if(isset($view)) {
//            switch($view) {
//                default:
//                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 60);
//            }
//        }
//        
//        return true;
//    }
    
    protected function _removeUnsecureFields(&$data) {
        
    }
}
