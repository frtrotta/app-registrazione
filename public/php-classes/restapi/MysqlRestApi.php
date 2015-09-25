<?php

namespace restapi;

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

        $this->conn = new \mysqli($this->mysqlServer, $this->mysqlUsername, $this->mysqlPassword, $this->mysqlDatabase);
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
        $view = $this->view;
        $selectionClause = $this->request;
        $id = $this->id;

        if (count($selectionClause)) {
            $r = $entityProxy->getSelected($selectionClause, $view, $removeUnsecureFields);
        } else {
            if (isset($id)) {
                $r = $entityProxy->get($id, $view, $removeUnsecureFields);
            } else {
                $r = $entityProxy->getAll($view, $removeUnsecureFields);
            }
        }
        return $r;
    }

    protected function _CRUDupdate($entityProxy) {
        $r = null;
        if (strpos($this->contentType, 'application/json') >= 0) {
            $data = $this->body;
            $id = $this->id;
            if ($data) {
                if (isset($id)) {
                    $r = $entityProxy->update($id, $data);
                } else {
                    throw new UnprocessableEntityException('Please provide an id', 100);
                }
            } else {
                throw new UnprocessableEntityException('Unable to parse JSON body', 101);
            }
        } else {
            throw new UnprocessableEntityException('Unexpected content type: ' . $this->contentType, 102);
        }
        return $r;
    }

    protected function _CRUDcreate($entityProxy, $view) {
        $r = null;
        if (strpos($this->contentType, 'application/json') >= 0) {
            $data = $this->body;
            $view = $this->view;
            if ($data) {
                //$this->conn->autocommit(false);
                $this->conn->begin_transaction();
                try {
                    $r = $entityProxy->add($data, $view);
                    $this->conn->commit();
                } catch (\Exception $ex) {
                    throw $ex;
                }
            } else {
                throw new UnprocessableEntityException('Unable to parse JSON body', 200);
            }
        } else {
            throw new UnprocessableEntityException('Unexpected content type: ' . $this->contentType, 201);
        }
        return $r;
    }

}
