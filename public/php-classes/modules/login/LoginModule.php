<?php

namespace modules\login;

class LoginModule {

    private $conn;
    private $gettoneValidoPerMinuti;
    private $meData;
    private $gettoneAutenticazione;
    private $cookieName;

    public function __construct(&$dbConnection, $authConf) {
        $this->conn = &$dbConnection;

        if (!isset($authConf['token-valid-for-minutes'])) {
            throw new LoginModuleException('$authConf[\'token-valid-for-minutes\'] is not set', 100);
        }

        if (!is_numeric($authConf['token-valid-for-minutes'])) {
            throw new LoginModuleException('$authConf[\'token-valid-for-minutes\'] must be numeric', 101);
        } else if (!(is_integer($authConf['token-valid-for-minutes'] + 0) && ((int) $authConf['token-valid-for-minutes']) > 0)) {
            throw new LoginModuleException('$authConf[\'token-valid-for-minutes\'] must be integer and positive', 102);
        }

        if (!isset($authConf['cookie-name'])) {
            throw new LoginModuleException('$authConf[\'cookie-name\'] is not set', 103);
        }

        $this->gettoneValidoPerMinuti = $authConf['token-valid-for-minutes'];
        $this->cookieName = $authConf['cookie-name'];
        $this->meData = NULL;

        $cookies = filter_input_array(INPUT_COOKIE);
        //var_export('Cookie: '.$cookies[$this->cookieName]);
        if (isset($cookies[$this->cookieName])) {
            $this->gettoneAutenticazione = $this->conn->escape_string($cookies[$this->cookieName]);
            //var_export('XXX $this->gettoneAutenticazione UNO ' . $this->gettoneAutenticazione);
            $this->_refreshGettoneAutenticazione();
            //var_export('XXX $this->gettoneAutenticazione DUE' . $this->gettoneAutenticazione);
            $this->_setCurrentUser();
        }
        
    }

    public function loginByEmailAndPassword($email, $password) {
        $r = false;
        if (isset($email) && isset($password) && $email !== '' && $password != '') {
            $email = $this->conn->escape_string($email);
            $password = $this->conn->escape_string($password);
            $query = 'SELECT'
                    . ' id,'
                    . ' nome,'
                    . ' cognome,'
                    . ' sesso,'
                    . ' natoIl, '
                    . ' email,'
                    . ' telefono,'
                    . ' eAmministratore'
                    . ' FROM utente'
                    . " WHERE email = '$email'"
                    . " AND password = '$password'";
            $rs = $this->conn->query($query);
            if ($this->conn->errno) {
                throw new LoginModuleException($this->conn->error, $this->conn->errno);
            }
            $row = $rs->fetch_assoc();
            if ($row) {
                $this->meData = $row;
                $this->_castCurrentUser();
                $r = true;
                $rs->free();

                $this->_setGettoneAutenticazione();
                $this->_setAuthCookie();
            }
        } else {
            throw new ClientRequestException('No email and/or password provided', 1);
        }

        return $r;
    }

    public function logout() {
        $this->_removeAuthCookie();
        return $this->_deleteGettoneAutenticazione();
    }

    public function userIsLogged() {
        return isset($this->meData);
    }

    public function userIsAmministratore() {
        return ($this->userIsLogged() && $this->meData['eAmministratore']);
    }

    public function me() {
        return $this->meData;
    }

    public function loginByFacebook($facebookAppUserId, $facebookAccessToken) {
        throw new Exception('Method not implemented');
    }

    private function _setCurrentUser() {
        $this->meData = NULL;
        if (isset($this->gettoneAutenticazione)) {
            $query = 'SELECT'
                    . ' id,'
                    . ' nome,'
                    . ' cognome,'
                    . ' sesso,'
                    . ' natoIl, '
                    . ' email,'
                    . ' telefono,'
                    . ' eAmministratore'
                    . ' FROM utente'
                    . " WHERE gettoneAutenticazione = '$this->gettoneAutenticazione'"
                    . " AND gettoneAutenticazioneScadeIl > NOW()";
            $rs = $this->conn->query($query);
            if ($this->conn->errno) {
                throw new LoginModuleException($this->conn->error, $this->conn->errno);
            }
            $row = $rs->fetch_assoc();
            if ($row) {
                $this->meData = $row;
                $this->_castCurrentUser();
                $rs->free();
            }
        }
    }

    private function _castCurrentUser() {
        $this->meData['id'] = (int) $this->meData['id'];
        $this->meData['eAmministratore'] = (boolean) $this->meData['eAmministratore'];
    }

    private function _createUniqueGettoneAutenticazione($id) {
        $unique = false;
        while (!$unique) {
            $gettone = sha1($id . 'pippo' . time());
            $query = 'SELECT '
                    . ' gettoneAutenticazione'
                    . ' FROM utente'
                    . " WHERE gettoneAutenticazione = '$gettone'";
            $rs = $this->conn->query($query);
            if ($this->conn->errno) {
                throw new LoginModuleException($this->conn->error, $this->conn->errno);
            }
            if ($rs) {
                if ($rs->num_rows === 0) {
                    $unique = true;
                    $rs->free();
                }
            } else {
                throw new LoginModuleException($this->conn->error, $this->conn->errno);
            }
        }
        return $gettone;
    }

    private function _setGettoneAutenticazione() {
        $id = $this->meData['id'];
        $this->gettoneAutenticazione = $this->_createUniqueGettoneAutenticazione($id);
        $query = 'UPDATE utente'
                . ' SET'
                . " gettoneAutenticazione = '$this->gettoneAutenticazione',"
                . " gettoneAutenticazioneScadeIl = DATE_ADD(NOW(), INTERVAL $this->gettoneValidoPerMinuti MINUTE)"
                . " WHERE id = '$id'";
        $this->conn->query($query);
        if ($this->conn->errno) {
            throw new LoginModuleException($this->conn->error, $this->conn->errno);
        }
        if ($this->conn->affected_rows !== 1) {
            throw new LoginModuleException('Number of affected rows: ' . $this->conn->affected_rows . ' (expected 1)', 30);
        }
    }

    private function _refreshGettoneAutenticazione() {
        if (isset($this->gettoneAutenticazione) && $this->gettoneAutenticazione !== '') {
            $query = 'UPDATE utente'
                    . ' SET'
                    . " gettoneAutenticazioneScadeIl = DATE_ADD(NOW(), INTERVAL $this->gettoneValidoPerMinuti MINUTE)"
                    . " WHERE gettoneAutenticazione = '$this->gettoneAutenticazione'"
                    . " AND gettoneAutenticazioneScadeIl > NOW()";
            $this->conn->query($query);
            if ($this->conn->errno) {
                throw new LoginModuleException($this->conn->error, $this->conn->errno);
            }
            switch ($this->conn->affected_rows) {
                case 0:
                    // token is not valid anymore
                    // XXX $this->gettoneAutenticazione = NULL;
                    break;
                case 1:
                    break;
                default:
                    throw new LoginModuleException('Unexpected number of affected rows: ' . $this->conn->affected_rows . ' (expected 1)', 11);
            }
        } else {
            throw new LoginModuleException('$this->gettoneAutenticazione is null o zero-length', 10);
        }
    }

    private function _deleteGettoneAutenticazione() {
        $r = false;
        if (isset($this->gettoneAutenticazione) && $this->gettoneAutenticazione !== '') {
            $query = 'UPDATE utente'
                    . ' SET'
                    . ' gettoneAutenticazione = NULL,'
                    . ' gettoneAutenticazioneScadeIl = NULL'
                    . " WHERE gettoneAutenticazione = '$this->gettoneAutenticazione'";
            $this->conn->query($query);
            if ($this->conn->errno) {
                throw new LoginModuleException($this->conn->error, $this->conn->errno);
            }
            switch ($this->conn->affected_rows) {
                case 1:
                    $r = true;
                    $this->gettoneAutenticazione = NULL;
                    break;
                case 0:
                    $this->gettoneAutenticazione = NULL;
                    break;
                default:
                    throw new LoginModuleException('Unexpected number of affected rows: ' . $this->conn->affected_rows . ' (expected 0 or 1)', 20);
            }
        }
        return $r;
    }

    private function _setAuthCookie() {
        setcookie($this->cookieName, $this->gettoneAutenticazione);
    }

    private function _removeAuthCookie() {
        setcookie($this->cookieName, '', time() - 60 * 60 * 24);
    }

}
