<?php

include_once '../objects/user.php';

class transaction {

    private $conn;
    private $table_name = "transaction";
    public $id;
    public $user_id;
    public $description;
    public $amount;
    public $currency;
    public $date;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = '" . $this->user_id. "'";
        $stmt = mysqli_query($this->conn, $query);
        return $stmt;
    }

    public function write() {

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->amount = htmlspecialchars(strip_tags($this->amount));
        $this->currency = htmlspecialchars(strip_tags($this->currency));
        $user = new user($this->conn);
        $user->id = $this->user_id;
        
        $update = $user->update_balance($this->amount);
        
        if (is_bool($update)){
            if($update){
                $query = "INSERT INTO " . $this->table_name . "(user_id, amount, currency, date, description) VALUES ('" .
                        $this->user_id . "'," . $this->amount . "," . $this->currency . ", CURRENT_DATE() ,'" . $this->description . "')";

                $stmt = mysqli_query($this->conn, $query);
                if($stmt) return true;
            } 
            return false;
         } else return $update;
    }

    public function read_period($from_date, $to_date) {

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));

        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = '" . $this->user_id. "' AND date >= STR_TO_DATE('".$from_date."', '%Y-%m-%d') AND date <= STR_TO_DATE('".$to_date."', '%Y-%m-%d')";
        $stmt = mysqli_query($this->conn, $query);
        return $stmt;
    }

}
