<?php

namespace dbproxy;

class CategoriaFitri extends MysqlProxyBase {
    public function __construct(&$connection) {
        parent::__construct($connection, 'categoria_fitri', ['nome',
            'nome_esteso',
            'iniza_da_anni']);
    }

    protected function _castData(&$data) {
        $data['inizia_da_anni'] = (int) $data['inizia_da_anni'];
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
        if (!isset($data['nome']) ||
                !isset($data['nomeEsteso']) ||
                !isset($data['iniziaDaAnni'])
        ) {
            return false;
        }
        if (!is_integer($data['iniziaDaAnni'])) {
            return false;
        }
        
        return true;
    }
    
    protected function _removeUnsecureFields(&$data) {
        
    }

}
