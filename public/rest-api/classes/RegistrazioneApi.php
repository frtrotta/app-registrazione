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
            $this->gettoneAutenticazione = filter_input(INPUT_COOKIE, $this->cookieName);
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
            $r = $this->_CRUDread($tf);
        } else {
            throw new MethodNotAllowedException('Method ' . $this->method . ' is not allowed');
        }
        return $r;
    }

    protected function Gara() {
        $r = null;
        $g = new dbproxy\Gara($this->conn);
        if ($this->method === 'GET') {
            $r = $this->_CRUDread($g);
        } else {
            throw new MethodNotAllowedException('Method ' . $this->method . ' is not allowed');
        }
        return $r;
    }

    protected function Utente() {
        $r = null;
        $u = new dbproxy\Utente($this->conn);
        if ($this->method === 'GET') {
            $r = $this->_CRUDread($u);
        } else if ($this->method === 'POST') {
            $r = $this->_CRUDupdate($u);
        } else if ($this->method === 'PUT') {

            // TODO Generazione password
            // TODO Invio email per completamento iscrizione. Sempre? No, solo in caso di autenticazione
            // classica
        } else {
            throw new MethodNotAllowedException("$this->method");
        }
        return $r;
    }

    /**
     * Performs the READ operation of the CRUD set.
     * 
     * @param MysqlProxyBase-dervird $entityProxy
     * @return the set of elements or the single element read
     * @throws RegistrazioneApiException in case of malformed selection clause
     */
    private function _CRUDread($entityProxy) {
        $r = null;
        if (count($this->request)) {
            $r = $entityProxy->getSelected($this->request, true);
        } else {
            if (isset($this->args[0])) {
                $id = $this->args[0];
                $r = $entityProxy->get($id, true);
            } else {
                $r = $entityProxy->getAll(true);
            }
        }
        return $r;
    }

//
//    private function _CRUDupdate($entityProxy) {
//        $r = null;
//        if ($this->contentType === 'application/json') {
//            $data = json_decode($this->body);
//            if ($data) {
//                $r = $entityProxy->update($data);
//            } else {
//                throw new BadRequestException('Unable to parse JSON body');
//            }
//        } else {
//            throw new BadRequestException('Unexpected content type: ' . $this->contentType);
//        }
//        return $r;
//    }
//    
//    private function _CRUDcreate($entityProxy) {
//        $r = null;
//        if ($this->contentType === 'application/json') {
//            $data = json_decode($this->body);
//            if ($data) {
//                $r = $entityProxy->add($data);
//            } else {
//                throw new BadRequestException('Unable to parse JSON body');
//            }
//        } else {
//            throw new BadRequestException('Unexpected content type: ' . $this->contentType);

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
        $rs = $this->conn->query($query);
        if ($this->conn->errno) {
            throw new Exception($this->conn->errno . ' ' . $this->conn->error);
        }
        $row = $rs->fetch_assoc();
        if ($row) {
            $this->me = $row;
            $this->_castCurrentUser();
            $r = true;
            $rs->free();
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
            if ($this->conn->errno) {
                throw new Exception($this->conn->errno . ' ' . $this->conn->error);
            }
            $row = $rs->fetch_assoc();
            if ($row) {
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
            if ($this->conn->errno) {
                throw new Exception($this->conn->errno . ' ' . $this->conn->error);
            }
            if ($rs) {
                if ($rs->num_rows === 0) {
                    $unique = true;
                    $rs->free();
                }
            } else {
                throw new Exception($this->conn->errno . ' ' . $this->conn->error);
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
        $this->conn->query($query);
        if ($this->conn->errno) {
            throw new Exception($this->conn->errno . ' ' . $this->conn->error);
        }
        if ($this->conn->affected_rows !== 1) {
            throw new InconsistentDataException('Number of affected rows: ' . $this->conn->affected_rows . ' (expected 1)');
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
                throw new Exception($this->conn->errno . ' ' . $this->conn->error);
            }
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
            $this->conn->query($query);
            if ($this->conn->errno) {
                throw new Exception($this->conn->errno . ' ' . $this->conn->error);
            }

            if ($this->conn->affected_rows === 1) {
                $this->gettoneAutenticazione = NULL;
                $r = true;
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
