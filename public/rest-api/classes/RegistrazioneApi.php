<?php

class RegistrazioneApi extends MySqlRestApi {

    private $loginModule;

    public function __construct($request, $mysqlConf, $authConf) {
        parent::__construct($request, $mysqlConf);
        /* The module must be created at the beginning, in order to correctly
         * refresh the authentication token in the database, if present.
         */
        $this->loginModule = new modules\LoginModule($this->conn, $authConf);
    }

    protected function Login() {
        if ($this->method === 'GET' || $this->method === 'POST') {
            // TODO no clear password
            if (isset($this->request['username']) && isset($this->request['password'])) {
                $username = $this->request['username'];
                $password = $this->request['password'];
                try {
                    if (!($this->loginModule->loginByUsernameAndPassword($username, $password))) {
                        throw new UnauthorizedException('Wrong username and/or password');
                    }
                } catch (LoginModuleException $ex) {
                    if ($ex->getCode() === 1) {
                        throw new BadRequestException('Please provide username and password');
                    } else {
                        throw $ex;
                    }
                }
            } else {
                throw new BadRequestException('Please provide username and password');
            }
        } else {
            throw new MethodNotAllowedException('Method ' . $this->method . ' is not allowed');
        }
        return 'Ok';
    }

    protected function Logout() {
        $r = null;
        if ($this->method === 'GET' || $this->method === 'POST') {
            $temp = $this->loginModule->logout();
            if($temp) {
                $r = 'Ok';
            }
            else {
                $r = 'No user to log out';
            }
        } else {
            throw new MethodNotAllowedException('Method ' . $this->method . ' is not allowed');
        }
        return $r;
    }

    protected function Me() {
        if ($this->method === 'GET') {
            return $this->loginModule->me();
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
}
