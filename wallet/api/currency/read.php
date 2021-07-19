<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/currency.php';

$database = new Database();
$db = $database->getConnection();

$currency = new currency($db);

$stmt = $currency->read();

$num = mysqli_num_rows($stmt);

if ($num>0) {

    $currency_arr=array();
    $currency_arr["records"]=array();

    while ($row = mysqli_fetch_row($stmt)){

        // извлекаем строку 
        $currency_item=array(
            "id" => $row[0],
            "name" => $row[1],
            "rate" => $row[2],
        );

        array_push($currency_arr["records"], $currency_item);
    }

    http_response_code(200);

    echo json_encode($currency_arr);
}

else {
    http_response_code(404);

    echo json_encode(array("message" => "Валют не знайденно."), JSON_UNESCAPED_UNICODE);
}