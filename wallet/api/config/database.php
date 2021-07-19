<?php
class Database {

    private $host = "localhost";
    private $db_name = "wallet";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection(){

        $this->conn = null;

        $this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);

        return $this->conn;
    }
}