<?php

if (!defined('DB_SERVER')) {
    require_once('../initialize.php');
}

class DBConnection 
{
    private $host = DB_SERVER;
    private $username = DB_USERNAME;
    private $password = DB_PASSWORD;
    private $dbname = DB_NAME;

    public $conn;

    public function __construct()
    {
        if (!isset($this->conn)) {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);

            if (!$this->conn) {
                echo "Não foi possível conectar ao servidor de Banco de Dados";
                exit;
            }
        }
    }

    public function __destruct()
    {
        $this->conn->close();
    }
}