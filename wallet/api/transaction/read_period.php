<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/transaction.php';

$database = new Database();
$db = $database->getConnection();

$transaction = new transaction($db);

$transaction->user_id = isset($_GET['id']) ? $_GET['id'] : die();



$stmt = $transaction->read_period($_GET['from'],$_GET['to']);

$num = mysqli_num_rows($stmt);

if ($num>0) {

    $transaction_arr=array();
    $transaction_arr["records"]=array();

    while ($row = mysqli_fetch_row($stmt)){

        $transaction_item=array(
            "id" => $row[0],
            "user_id" => $row[1],
            "amount" => $row[2],
            "currency" => $row[3],
            "date" => $row[4],
            "description" => $row[5]
        );

        array_push($transaction_arr["records"], $transaction_item);
    }

    http_response_code(200);

    echo json_encode($transaction_arr);
}

else {

    http_response_code(404);

    echo json_encode(array("message" => "Транзакцій за період не знайденно"), JSON_UNESCAPED_UNICODE);
}