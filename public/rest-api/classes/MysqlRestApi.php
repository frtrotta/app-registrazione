<?php

class MysqlRestApi extends RestApi {

    private $mysqlServer;
    private $mysqlUsername;
    private $mysqlPassword;
    private $mysqlDatabase;
    protected $conn;

    public function __construct($request, $mysqlConf) {
        parent::__construct($request);

        if (!isset($mysqlConf['server'])) {
            throw new Exception('$mysqlConf[\'server\'] is not set');
        }

        if (!isset($mysqlConf['username'])) {
            throw new Exception('$mysqlConf[\'username\'] is not set');
        }

        if (!isset($mysqlConf['password'])) {
            throw new Exception('$mysqlConf[\'password\'] is not set');
        }

        if (!isset($mysqlConf['database'])) {
            throw new Exception('$mysqlConf[\'database\'] is not set');
        }

        $this->mysqlServer = $mysqlConf['server'];
        $this->mysqlUsername = $mysqlConf['username'];
        $this->mysqlPassword = $mysqlConf['password'];
        $this->mysqlDatabase = $mysqlConf['database'];

        $this->conn = new mysqli($this->mysqlServer, $this->mysqlUsername, $this->mysqlPassword, $this->mysqlDatabase);
        if ($this->conn->connect_errno) {
            throw new Exception("Connection error: $this->conn->connect_error");
        }
    }

    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }

    protected function fetch_all_assoc($result_set) {
        $r = NULL;
        if ($result_set) {
            $r = [];
            while ($row = $result_set->fetch_assoc()) {
                array_push($r, $row);
            }
            $result_set->free();
        }
        return $r;
    }

    /**
     * Performs the READ operation of the CRUD set.
     * 
     * @param MysqlProxyBase-derived $entityProxy
     * @param boolean $removeUnsecureFields
     * @return the set of elements or the single element read
     */
    protected function _CRUDread($entityProxy, $removeUnsecureFields = true) {
        $r = null;
        if (count($this->request)) {
            $r = $entityProxy->getSelected($this->request, true, $removeUnsecureFields);
        } else {
            if (isset($this->args[0])) {
                $id = $this->args[0];
                $r = $entityProxy->get($id, true, $removeUnsecureFields);
            } else {
                $r = $entityProxy->getAll(true, $removeUnsecureFields);
            }
        }
        return $r;
    }

    protected function _CRUDupdate($entityProxy) {
        $r = null;
        if ($this->contentType === 'application/json') {
            $data = $this->body;
            if ($data) {
                if (isset($this->args[0])) {
                    $id = $this->args[0];
                    $r = $entityProxy->update($id, $data);
                }
                else {
                    // TODO Provide identifier
                }
            } else {
                throw new BadRequestException('Unable to parse JSON body');
            }
        } else {
            throw new BadRequestException('Unexpected content type: ' . $this->contentType);
        }
        return $r;
    }

    protected function _CRUDcreate($entityProxy) {
        $r = null;
        if ($this->contentType === 'application/json') {
            $data = $this->body;
            if ($data) {
                $r = $entityProxy->add($data);
            } else {
                throw new BadRequestException('Unable to parse JSON body');
            }
        } else {
            throw new BadRequestException('Unexpected content type: ' . $this->contentType);
        }
        return $r;
    }

}