<?php

namespace dbproxy;

class TesseratiFitri extends MysqlProxyBase {

    public function __construct(&$connection) {
        parent::__construct($connection, 'tesserati_fitri', ['TESSERA',
            'CODICE_SS',
            'COGNOME',
            'NOME',
            'SESSO',
            'DATA_NASCITA',
            'CITTADINANZA',
            'CATEGORIA',
            'QUALIFICA',
            'LIVELLO',
            'STATO',
            'DATA_EMISSIONE',
            'TIPO_TESSERA',
            'DISABILITA']);
    }

    protected function _castData(&$data) {
        $data['TESSERA'] = (int) $data['TESSERA'];
        $data['CODICE_SS'] = (int) $data['CODICE_SS'];
        //TODO $data['DATA_NASCITA'] = new DateTime($data['DATA_NASCITA']);
        //TODO $data['DATA_EMISSIONE'] = new DateTime($data['DATA_EMISSIONE']);
    }

    protected function _complete(&$data) {
        $sf = new SocietaFitri($this->conn);
        $data['societa'] = $sf->get($data['CODICE_SS'], true);
        unset($data['CODICE_SS']);
    }

    protected function _isCoherent($data) {
        if (!isset($data['CODICE_SS']) ||
                !isset($data['TESSERA']) ||
                !isset($data['COGNOME']) ||
                !isset($data['NOME']) ||
                !isset($data['SESSO']) ||
                !isset($data['DATA_NASCITA']) ||
                !isset($data['CITTADINANZA']) ||
                !isset($data['CATEGORIA']) ||
                !isset($data['QUALIFICA']) ||
                !isset($data['DATA_EMISSIONE']) ||
                !isset($data['TIPO_TESSERA'])
        ) {
            return false;
        }
        if (!is_integer($data['CODICE_SS'])) {
            return false;
        }

        if (!is_integer($data['TESSERA'])) {
            return false;
        }        
        
        if(!$this->_is_string_with_length($data['COGNOME'])) {
            return false;
        }
        
        if(!$this->_is_string_with_length($data['NOME'])) {
            return false;
        }       
        
        if(!$this->_is_string_with_length($data['SESSO'])) {
            return false;
        }
        
        if (!$this->_is_date($data['DATA_NASCITA'])) {
            return false;
        }        
        
        if(!$this->_is_string_with_length($data['CITTADINANZA'])) {
            return false;
        }
        
        if(!$this->_is_string_with_length($data['CATEGORIA'])) {
            return false;
        }       
        
        if(!$this->_is_string_with_length($data['QUALIFICA'])) {
            return false;
        }
        
        if(!$this->_is_string_with_length_optional(@$data['LIVELLO'])) {
            return false;
        }
        
        if(!$this->_is_string_with_length_optional(@$data['STATO'])) {
            return false;
        }
        
        if (!$this->_is_date($data['DATA_EMISSIONE'])) {
            return false;
        }       
        
        if(!$this->_is_string_with_length($data['TIPO_TESSERA'])) {
            return false;
        }
        
        if(!$this->_is_string_with_length_optional(@$data['DISABILITA'])) {
            return false;
        }
        
        return true;
    }

    
    protected function _removeUnsecureFields(&$data) {
        
    }
}
