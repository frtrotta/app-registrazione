<?php

class RegistrazioneApi extends MysqlRestApi {

    private $loginModule;

    public function __construct($request, $mysqlConf, $authConf) {
        parent::__construct($request, $mysqlConf);
        /* The module must be created at the beginning, in order to correctly
         * refresh the authentication token in the database, if present.
         */
        $this->loginModule = new modules\LoginModule($this->conn, $authConf);
    }

    protected function Login() {
        $email = null;
        $password = null;
        switch ($this->method) {
            case 'GET':
                if (isset($this->request['email'])) {
                    $email = $this->request['email'];
                }
                if (isset($this->request['password'])) {
                    $password = $this->request['password'];
                }
                break;
            case 'POST':
                if (isset($this->body['email'])) {
                    $email = $this->body['email'];
                }
                if (isset($this->body['password'])) {
                    $password = $this->body['password'];
                }
                break;
            default:
                throw new MethodNotAllowedException('Method ' . $this->method . ' is not allowed');
        }
        try {
            if (!($this->loginModule->loginByEmailAndPassword($email, $password))) {
                throw new UnauthorizedException('Wrong email and/or password');
            }
        } catch (modules\ClientRequestException $ex) {
            if ($ex->getCode() === 0) {
                throw new BadRequestException('Please provide email and password');
            } else {
                throw $ex;
            }
        }
        return 'Ok';
    }

    protected function Logout() {
        $r = null;
        if ($this->method === 'GET' || $this->method === 'POST') {
            $temp = $this->loginModule->logout();
            if ($temp) {
                $r = 'Ok';
            } else {
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
        switch ($this->method) {
            case 'GET':
                // TODO solo amministratori possono leggere la lista di tutti gli utenti
                // gli altri possono solo verificare se email esiste
                $r = $this->_CRUDread($u);
                break;
            case 'POST':
            case 'PUT':
                // creation and update
                $id = null;
                if (strpos($this->contentType, 'application/json') >= 0) {
                    if (isset($this->args[0]) && is_numeric($this->args[0])) {
                        $id = (int) $this->args[0];
                    }
                } else {
                    throw new BadRequestException('Unexpected content type: ' . $this->contentType);
                }

                $authorized = false;
                if (is_integer($id)) {
                    // update permissions
                    if ($this->loginModule->userIsAmministratore()) {
                        $authorized = true;
                    }
                } else {
                    // creation permissions
                    if (isset($this->body['eAmministratore'])) {
                        if ((bool) $this->body['eAmministratore']) {
                            if ($this->loginModule->userIsAmministratore()) {
                                $authorized = true;
                            }
                        } else {
                            $authorized = true;
                        }
                    } else {
                        $authorized = true; // It won't pass coherence check
                    }
                }

                if (is_integer($id)) {
                    // update permissions
                    if ($authorized) {
                        $r = $this->_CRUDupdate($u);
                    } else {
                        throw new UnauthorizedException('User must be Amministratore to update users');
                    }
                } else {
                    // creation
                    if ($authorized) {
                        $r = $this->_CRUDcreate($u);
                    } else {
                        throw new UnauthorizedException('User must be Amministratore to create an Amministratore');
                    }
                }

                break;
            default:
                throw new MethodNotAllowedException('Method ' . $this->method . ' is not allowed');
        }
        return $r;
    }

}
