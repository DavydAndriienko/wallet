<?php

class user {

    private $conn;
    private $table_name = "users";
    public $id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($user_id, $limit) {
 /*       $query = "SELECT * FROM " . $this->table_name . " WHERE user_id='" . $user_id . "'";
        $stmt = mysqli_query($this->conn, $query);
        $num= mysqli_num_rows($stmt);
        if ($num>0) {
            return false;
        }*/
        $query = "INSERT INTO " . $this->table_name . " (user_id, balance, day_limit, day_used, limit_upd_time) VALUES('" . $user_id . "',0," . $limit . ",0, CURRENT_DATE())";
        $stmt = mysqli_query($this->conn, $query);
        if ($stmt) {
            return true;
        }
        return false;
    }

    public function get_balance() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = '" . $this->id . "'";
        $stmt = mysqli_query($this->conn, $query);
        if ($stmt) {
            return $stmt;
        }
        return false;
    }

    public function update_balance($difference) {
        if ($difference < 0) {
            $stmt = $this->get_balance();
            $row = mysqli_fetch_row($stmt);
            if ($row[1] < abs($difference)) {
                return "Недостатньо коштів для операції";
            }
            if ($row[2] < abs($difference)) {
                return "Денний ліміт вичерпаний, або сумма перевищує встановленний ліміт";
            }
            $query = "SELECT CURRENT_DAY()";
            $cur_day= mysqli_query($this->conn, $query);
            if ($row[4]<$cur_day){
                $query = "UPDATE ". $this->table_name. " SET balance = balance +". $difference. " day_used = ". abs($difference). " limit_upd_time = CURRENT_DAY() WHERE user_id = '".$this->id. "'";
                $stmt= mysqli_query($this->conn, $query);
                if($stmt) {
                    return true;
                } else return false;
            }
            if (abs($difference)+$row[3]>$row[2]){
                return "Денний ліміт вичерпаний, або сумма перевищує встановленний ліміт";
            }
            $query="UPDATE " . $this->table_name . " SET day_used = day_used + " . abs($difference) . ", balance = balance +" . $difference . " WHERE user_id = '" . $this->id . "'";
            $stmt= mysqli_query($this->conn, $query);
            if($stmt){
                return true;
            } else return false;

        } else {
            $query = "UPDATE " . $this->table_name . " SET balance = balance +" . $difference . " WHERE user_id = '" . $this->id. "'";
            $stmt = mysqli_query($this->conn, $query);
            if ($stmt) {
                return true;
            } else {
                return false;
            }
        }
    }

}
