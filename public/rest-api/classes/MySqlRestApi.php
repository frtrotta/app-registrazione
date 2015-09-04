<?php

class MySqlRestApi extends RestApi {

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
        if($this->conn)
            $this->conn->close();
    }
    
    public function fetch_all_assoc($result_set) {
        $r = NULL;
        if($result_set) {
            $r = [];
            while ($row = $result_set->fetch_assoc()) {
                array_push($r, $row);
            }
            $result_set->free();
        }
        return $r;
    }
}
