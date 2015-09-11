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
        if (!isset($data['id']) ||
                !isset($data['nome']) ||
                !isset($data['cognome']) ||
                !isset($data['sesso']) ||
                !isset($data['natoIl']) ||
                !isset($data['email']) ||
                !isset($data['eAmministratore'])
        ) {
            return false;
        }
        if (!is_integer($data['id'])) {
            return false;
        }

        if (!is_bool($data['eAmministratore'])) {
            return false;
        }

        if (!$this->_is_date($data['natoIl'])) {
            return false;
        }

        // ---- opzionali

        if (!$this->_is_date_optional($data['gettoneAutenticazionScadeIl'])) {
            return false;
        }

        // --- combinazioni

        if ((isset($data['password']) && isset($data['facebookId'])) ||
                (isset($data['facebookId']) && !isset($data['password']))) {
            return false;
        }

        return true;
    }

    public function removeUnsecureFields(&$data) {
        unset($data['password']);
        unset($data['gettoneAutenticazione']);
        unset($data['gettoneAutenticazioneScadeIl']);
    }

    public function add($data) {
        $r = null;
        // TODO stored functions?
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
                throw new Exception($this->conn->error, $this->conn->errno);
            }
            switch ($rs->num_rows) {
                case 1:
                    $row = $rs->fetch_row();
                    $exists = true;
                    $r['email'] = $row[0];
                /* Restituisce email con cui è iscritto, che potrebbe essere la medesima,
                 * oppure un'altra.
                 */
                case 0:
                    $rs->free();
                    break;
                default:
                    throw new MysqlProxyBaseException('Unexpected number of results: ' . $rs->num_rows . ' (expected 0 or 1)', 40);
            }

            // poi guardo se esiste con stesso nome, cognome, sesso, ma con data di nascita 26/giu/2014
            // in questo caso, faccio un aggiornamento dei dati
            if (!exists) {
                $query = 'SELECT id FROM utente WHERE '
                        . ' nome = ' . $this->_sqlFormat($nome) . ' AND'
                        . ' cognome = ' . $this->_sqlFormat($cognome) . ' AND'
                        . ' sesso = ' . $this->_sqlFormat($sesso) . 'AND'
                        . ' natoIl = 2014/06/29';
                $rs = $this->conn->query($query);
                if ($this->conn->errno) {
                    throw new Exception($this->conn->error, $this->conn->errno);
                }
                switch ($rs->num_rows) {
                    case 1:
                        $row = $rs->fetch_row();
                        $rs->free();
                        $exists = true;
                        $data['id'] = (int) $row[0];
                        // Aggiorno i dati inseriti
                        $this->update($data);
                        $r['id'] = $data['id'];
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
                $r['id'] = parent::add($data);
            }
        } else {
            $e = var_export($data, true);
            throw new MysqlProxyBaseException("Incoherent data $e", 29);
        }
        return $r;
    }

}
