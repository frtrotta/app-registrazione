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

    protected function _complete(&$data, $view) {
        if (isset($view)) {
            switch ($view) {
                case 'invito':
                case 'ordine':
                case 'descrizione':
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
        if (!isset($data['nome'])) {
            return 'nome is missing';
        }
        if (!isset($data['cognome'])) {
            return 'cognome is missing';
        }
        if (!isset($data['sesso'])) {
            return 'sesso is missing';
        }
        if (!isset($data['natoIl'])) {
            return 'natoIl is missing';
        }
        if (!isset($data['email'])) {
            return 'email is missing';
        }
        if (!isset($data['eAmministratore'])) {
            return 'eAmministratore is missing';
        }
        
        if (!$this->_is_integer_optional(@$data['id'])) {
            return 'is is set but it is not integer';
        }

        if (!$this->_is_string_with_length($data['nome'])) {
            return 'nome is a 0-length string';
        }

        if (!$this->_is_string_with_length($data['cognome'])) {
            return 'cognome is a 0-length string';
        }

        if (!$this->_is_string_with_length($data['sesso'])) {
            return 'sesso is a 0-length string';
        }

        if (!$this->_is_string_with_length($data['email'])) {
            return 'email is a 0-length string';
        }

        if (!is_bool($data['eAmministratore'])) {
            return 'eAmministratore is not boolean';
        }

        if (!$this->_is_date($data['natoIl'])) {
            return 'natoIl is not a valid datetime';
        }

        // ---- opzionali

        if (!$this->_is_string_with_length_optional(@$data['password'])) {
            return 'password is set but it is a 0-length string';
        }

//        if (!$this->_is_string_with_length_optional(@$data['gettoneAutenticazione'])) {
//            return false;
//        }

        if (!$this->_is_string_with_length_optional(@$data['telefono'])) {
            return 'telefono is set but it is a 0-length string';
        }

        if (!$this->_is_string_with_length_optional(@$data['facebookId'])) {
            return 'facebookId is set but it is a 0-length string';
        }

//        if (!$this->_is_date_optional(@$data['gettoneAutenticazioneScadeIl'])) {
//            return false;
//        }

        // --- combinazioni

        if (!isset($data['facebookId']) && !isset($data['password'])) {
            return 'Either facebookId or password must be set';
        }

        if (isset($view)) {
            switch ($view) {
                default:
                    throw new ClientRequestException('Unsupported view for ' . get_class($this) . ': ' . $view, 60);
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
        $r = null;
        unset($data['gettoneAutenticazione']);
        unset($data['gettoneAutenticazioneScadeIl']);

        $check = $this->_isCoherent($data, null);
        if ($check !== true) {
            throw new ClientRequestException('Incoherent data for ' . get_class($this) . ". $check.", 90);
        }
        $r = $this->_baseUpdate($id, $data);
        return $r;
    }

    public function add($data, $view) {
        unset($data['gettoneAutenticazione']);
        unset($data['gettoneAutenticazioneScadeIl']);

        $check = $this->_isCoherent($data, $view);
        if ($check !== true) {
            throw new ClientRequestException('Incoherent data for ' . get_class($this) . ". $check.", 91);
        }

        $r = null;
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
                    $r = $this->_baseUpdate((int) $row[0], $data);
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
            $r = $this->_baseAdd($data);
        }
        return $r;
    }

}
