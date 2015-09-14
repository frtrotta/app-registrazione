<?php

namespace dbproxy;

class Utente extends MysqlProxyBase {

    public function __construct(&$connection) {
        parent::__construct($connection, 'utente', ['id',
            'password',
            'gettoneAutenticazione',
            'gettoneAutenticazioneScadeIl',
            'nome',
            'cognome',
            'sesso',
            'natoIl',
            'email',
            'facebookId',
            'telefono',
            'eAmministratore']);
    }

    protected function _castData(&$data) {
        $data['id'] = (int) $data['id'];
        $data['eAmministratore'] = (boolean) $data['eAmministratore'];
    }

    protected function _complete(&$data) {
        
    }

    protected function _isCoherent($data) {
        if (!array_key_exists('id', $data) ||
                !isset($data['nome']) ||
                !isset($data['cognome']) ||
                !isset($data['sesso']) ||
                !isset($data['natoIl']) ||
                !isset($data['email']) ||
                !isset($data['eAmministratore'])
        ) {
            return false;
        }

        if (isset($data['id']) && !is_integer($data['id'])) {
            return false;
        }

        if (!$this->_is_string_with_length($data['nome'])) {
            return false;
        }

        if (!$this->_is_string_with_length($data['cognome'])) {
            return false;
        }

        if (!$this->_is_string_with_length($data['sesso'])) {
            return false;
        }

        if (!$this->_is_string_with_length($data['email'])) {
            return false;
        }

        if (!is_bool($data['eAmministratore'])) {
            return false;
        }

        if (!$this->_is_date($data['natoIl'])) {
            return false;
        }

        // ---- opzionali

        if (!$this->_is_string_with_length_optional(@$data['password'])) {
            return false;
        }

        if (!$this->_is_string_with_length_optional(@$data['gettoneAutenticazione'])) {
            return false;
        }

        if (!$this->_is_string_with_length_optional(@$data['telefono'])) {
            return false;
        }

        if (!$this->_is_string_with_length_optional(@$data['facebookId'])) {
            return false;
        }

        if (!$this->_is_date_optional(@$data['gettoneAutenticazioneScadeIl'])) {
            return false;
        }

        // --- combinazioni

        if (!isset($data['facebookId']) && !isset($data['password'])) {
            return false;
        }
        
        if (isset($data['password']) && isset($data['facebookId'])) {
            if ($this->_is_string_with_length($data['facebookId']) &&
                    $this->_is_string_with_length($data['password'])) {
                return false;
            }
        }

        return true;
    }

    protected function _removeUnsecureFields(&$data) {
        unset($data['password']);
        unset($data['gettoneAutenticazione']);
        unset($data['gettoneAutenticazioneScadeIl']);
        // TODO unset($data['facebookId']);
    }
    
    public function update($id, $data) {        
        unset($data['gettoneAutenticazione']);
        unset($data['gettoneAutenticazioneScadeIl']);
        parent::update($id, $data);
    }

    public function add($data) {
        unset($data['gettoneAutenticazione']);
        unset($data['gettoneAutenticazioneScadeIl']);

        $r = null;
        if ($this->_isCoherent($data)) {
            $nome = $data['nome'];
            $cognome = $data['cognome'];
            $email = $data['email'];
            $sesso = $data['sesso'];
            $natoIl = $data['natoIl'];

            $exists = false;
            // prima devo guardare se esiste utente con stesso nome, cognome, sesso e data di nascita.
            // se ha la stessa mail, non faccio niente. se ha mail diversa, non aggiungo e comunico
            // l'esistenza di un'isrcrizione con mail diversa
            $query = 'SELECT email FROM utente WHERE '
                    . ' nome = ' . $this->_sqlFormat($nome) . ' AND'
                    . ' cognome = ' . $this->_sqlFormat($cognome) . ' AND'
                    . ' sesso = ' . $this->_sqlFormat($sesso) . 'AND'
                    . ' natoIl = ' . $this->_sqlFormat($natoIl);
            $rs = $this->conn->query($query);
            if ($this->conn->errno) {
                throw new MysqlProxyBaseException($this->conn->error, $this->conn->errno);
            }
            switch ($rs->num_rows) {
                case 1:
                    $row = $rs->fetch_row();
                    $exists = true;
                    $email = $row[0];
                    $rs->free();
                    throw new ClientRequestException("User exists with email $email", 50);
                case 0:
                    $rs->free();
                    break;
                default:
                    throw new MysqlProxyBaseException('Unexpected number of results: ' . $rs->num_rows . ' (expected 0 or 1)', 40);
            }

            // poi guardo se esiste con stesso nome, cognome, sesso, ma con data di nascita 26/giu/2014:
            // in questo caso, faccio un aggiornamento dei dati
            if (!$exists) {
                $query = 'SELECT id FROM utente WHERE '
                        . ' nome = ' . $this->_sqlFormat($nome) . ' AND'
                        . ' cognome = ' . $this->_sqlFormat($cognome) . ' AND'
                        . ' sesso = ' . $this->_sqlFormat($sesso) . ' AND'
                        . ' natoIl = \'2014/06/29\'';
                $rs = $this->conn->query($query);

                if ($this->conn->errno) {
                    throw new MysqlProxyBaseException($this->conn->error, $this->conn->errno);
                }

                switch ($rs->num_rows) {
                    case 1:
                        $row = $rs->fetch_row();
                        $rs->free();
                        $exists = true;
                        // Aggiorno i dati inseriti
                        $r = $this->update((int) $row[0], $data);
                        break;
                    case 0:
                        $rs->free();
                        break;
                    default:
                        throw new MysqlProxyBaseException('Unexpected number of results: ' . $rs->num_rows . ' (expected 0 or 1)', 41);
                }
            }

            // Se non esiste, procedo normalmente
            if (!$exists) {
                $r = parent::add($data);
            }
        } else {
            $e = var_export($data, true);
            throw new ClientRequestException('Incoherent data. The data you provided did not meet expectations: please checkt and try again.', 90);
        }
        return $r;
    }

}
