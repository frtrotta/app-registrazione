<?php

class RegistrazioneApi extends MySqlRestApi {

    private $gettoneValidoPerMinuti;
    private $me;
    private $gettoneAutenticazione;
    private $cookieName;

    public function __construct($request, $mysqlConf, $authConf) {
        parent::__construct($request, $mysqlConf);

        if (!isset($authConf['token-valid-for-minutes'])) {
            throw new Exception('$authConf[\'token-valid-for-minutes\'] is not set');
        }

        if (!is_numeric($authConf['token-valid-for-minutes'])) {
            throw new Exception('$authConf[\'token-valid-for-minutes\'] must be numeric');
            // TODO integer and positive
        } else if (!(is_integer($authConf['token-valid-for-minutes'] + 0) && ((int) $authConf['token-valid-for-minutes']) > 0)) {
            throw new Exception('$authConf[\'token-valid-for-minutes\'] must be integer and positive');
        }

        if (!isset($authConf['cookie-name'])) {
            throw new Exception('$authConf[\'cookie-name\'] is not set');
        }

        $this->gettoneValidoPerMinuti = $authConf['token-valid-for-minutes'];
        $this->cookieName = $authConf['cookie-name'];
        $this->me = NULL;

        if (isset($_COOKIE[$this->cookieName])) {
            $this->gettoneAutenticazione = filter_input(INPUT_COOKIE, $this->cookieName, FILTER_SANITIZE_STRING);
            $this->gettoneAutenticazione = $this->conn->escape_string($this->gettoneAutenticazione);
            $this->_refreshGettoneAutenticazione();
            $this->_setCurrentUser();
        }
    }

    protected function Login() {
        if ($this->method === 'GET' || $this->method === 'POST') {
            // TODO no clear password
            if (isset($this->request['username']) && isset($this->request['password'])) {
                $username = $this->conn->escape_string($this->request['username']);
                $password = $this->conn->escape_string($this->request['password']);
                if ($this->_loginByUsernameAndPassword($username, $password)) {
                    $this->_setGettoneAutenticazione();
                    $this->_setAuthCookie();
                } else {
                    throw new UnauthorizedException('Wrong username and/or password');
                }
            } else {
                // TODO no cleaar password
                throw new BadRequestException('Please provide username and password');
            }
        } else {
            throw new MethodNotAllowedException('Method ' . $this->method . ' is not allowed');
        }
        return $this->gettoneAutenticazione;
    }

    protected function Logout() {
        if ($this->method === 'GET') {
            if ($this->_deleteGettoneAutenticazione()) {
                $this->_removeAuthCookie();
            } else {
                throw new BadRequestException('No valid auth cookie');
            }
        } else {
            throw new MethodNotAllowedException('Method ' . $this->method . ' is not allowed');
        }
        return "ok";
    }

    protected function Me() {
        if ($this->method === 'GET') {
            return $this->me;
        } else {
            throw new MethodNotAllowedException('Method ' . $this->method . ' is not allowed');
        }
    }

    protected function TesseratiFitri() {
        $r = null;
        if ($this->method === 'GET') {
            $tf = new dbproxy\TesseratiFitri($this->conn);
            $queryString = filter_input(INPUT_SERVER, 'QUERY_STRING');
            $queryString = urldecode($queryString);
            $queryString = explode('&', $queryString);
            if (isset($queryString[1])) {
                $queryString = $queryString[1];
                $whereClause = json_decode($queryString, true);
                if ($whereClause) {
                    $r = $tf->getSelected($whereClause, true);
                } else {
                    throw new RegistrazioneApiException('Malformed selection clause: ' . $queryString, 10);
                }
            } else {
                if (isset($this->args[0])) {
                    $id = $this->args[0];
                    $r = $tf->get($id, true);
                } else {
                    $r = $tf->getAll(true);
                }
            }
        } else {
            throw new MethodNotAllowedException('Method ' . $this->method . ' is not allowed');
        }
        return $r;
    }
    
    protected function Gara() {
        $r = null;
        if ($this->method === 'GET') {
            $tf = new dbproxy\Gara($this->conn);
            $queryString = filter_input(INPUT_SERVER, 'QUERY_STRING');
            $queryString = urldecode($queryString);
            $queryString = explode('&', $queryString);
            if (isset($queryString[1])) {
                $queryString = $queryString[1];
                $whereClause = json_decode($queryString, true);
                if ($whereClause) {
                    $r = $tf->getSelected($whereClause, true);
                } else {
                    throw new RegistrazioneApiException('Malformed selection clause: ' . $queryString, 10);
                }
            } else {
                if (isset($this->args[0])) {
                    $id = $this->args[0];
                    $r = $tf->get($id, true);
                } else {
                    $r = $tf->getAll(true);
                }
            }
        } else {
            throw new MethodNotAllowedException('Method ' . $this->method . ' is not allowed');
        }
        return $r;
    }

    private function _loginByUsernameAndPassword($username, $password) {
        $r = false;
        $query = 'SELECT'
                . ' id,'
                . ' username, '
                . ' nome,'
                . ' cognome,'
                . ' sesso,'
                . ' natoIl, '
                . ' email,'
                . ' telefono,'
                . ' eAmministratore'
                . ' FROM utente'
                . " WHERE username = '$username'"
                . " AND password = '$password'";
        if ($rs = $this->conn->query($query)) {
            if ($row = $rs->fetch_assoc()) {
                $this->me = $row;
                $this->_castCurrentUser();
                $rs->free();
                $r = true;
            }
        } else {
            throw new Exception($this->conn->error);
        }

        return $r;
    }

    private function _setCurrentUser() {
        $this->me = NULL;
        if (isset($this->gettoneAutenticazione)) {
            $query = 'SELECT'
                    . ' id,'
                    . ' username,'
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
            if ($row = $rs->fetch_assoc()) {
                $this->me = $row;
                $this->_castCurrentUser();
                $rs->free();
            }
        }
    }

    private function _castCurrentUser() {
        $this->me['id'] = (int) $this->me['id'];
        $this->me['eAmministratore'] = (boolean) $this->me['eAmministratore'];
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
            if ($rs) {
                if ($rs->num_rows === 0) {
                    $unique = true;
                    $rs->free();
                }
            } else {
                throw new Exception($this->conn->error);
            }
        }
        return $gettone;
    }

    private function _setGettoneAutenticazione() {
        $id = $this->me['id'];
        $this->gettoneAutenticazione = $this->_createUniqueGettoneAutenticazione($id);
        $query = 'UPDATE utente'
                . ' SET'
                . " gettoneAutenticazione = '$this->gettoneAutenticazione',"
                . " gettoneAutenticazioneScadeIl = DATE_ADD(NOW(), INTERVAL $this->gettoneValidoPerMinuti MINUTE)"
                . " WHERE id = '$id'";
        if ($this->conn->query($query)) {
            if ($this->conn->affected_rows !== 1) {
                throw new InconsistentDataException("Number of affected rows: $$this->conn->affected_rows (expected 1)");
            }
        } else {
            throw new Exception($this->conn->error);
        }
    }

    private function _refreshGettoneAutenticazione() {
        if (isset($this->gettoneAutenticazione) && $this->gettoneAutenticazione !== '') {
            $query = 'UPDATE utente'
                    . ' SET'
                    . " gettoneAutenticazioneScadeIl = DATE_ADD(NOW(), INTERVAL $this->gettoneValidoPerMinuti MINUTE)"
                    . " WHERE gettoneAutenticazione = '$this->gettoneAutenticazione',"
                    . " AND gettoneAutenticazioneScadeIl > NOW()";
            $this->conn->query($query);
            if ($this->conn->affected_rows === 0) {
                $this->gettoneAutenticazione = NULL;
                //throw new InconsistentDataException('Number of affected rows: '. $this->conn->affected_rows .' (expected 1)');
            }
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
            if ($this->conn->query($query)) {
                if ($this->conn->affected_rows === 1) {
                    $this->gettoneAutenticazione = NULL;
                    $r = true;
                }
            } else {
                throw new Exception("[$query]");
            }
        }
        return $r;
    }

    private function _userIsLogged() {
        return isset($this->me);
    }

    private function _userIsAmministratore() {
        return (_userIsLooged() && $this->me['eAmministratore']);
    }

    private function _setAuthCookie() {
        setcookie($this->cookieName, $this->gettoneAutenticazione);
    }

    private function _removeAuthCookie() {
        setcookie($this->cookieName, "", time() - 60 * 60 * 24);
    }

    private function _loginByFacebookApplicationId($id) {
        throw new Exception('Method not implemented');
    }

}
