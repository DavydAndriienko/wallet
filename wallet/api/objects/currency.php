<?php

class currency {
 
    private $conn;
    private $table_name = "currency";
    
    public function __construct($db){
        $this->conn = $db;
    }
    
    public function read(){
        
        $query = "SELECT * FROM ". $this->table_name;
        $stmt = mysqli_query($this->conn, $query);
        
        if($stmt){
            return $stmt;
        }
        
        return false;
        
    }
}
