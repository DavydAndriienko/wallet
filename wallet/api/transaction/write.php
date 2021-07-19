<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

include_once '../objects/transaction.php';

$database = new Database();
$db = $database->getConnection();

$transaction = new transaction($db);

$data = json_decode(file_get_contents("php://input"));

if (
        !empty($data->id) &&
        !empty($data->amount) &&
        !empty($data->description) &&
        !empty($data->currency)
) {

    $transaction->user_id = $data->id;
    $transaction->amount = $data->amount;
    $transaction->description = $data->description;
    $transaction->currency = $data->currency;

    $result = $transaction->write();
    if (is_bool($result)) {
        if($result){
            http_response_code(201);
            echo json_encode(array("message" => "Операція додана"), JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(501);
            echo json_encode(array("message" => "Операція не додана, щось пішло не так"), JSON_UNESCAPED_UNICODE);
        }
    } else {
        http_response_code(200);
        echo json_encode(array("message" => "Операція не додана по причині ".$result), JSON_UNESCAPED_UNICODE);
    }
}

else {

    http_response_code(400);

    echo json_encode(array("message" => "Неможливо додати операцію, данні не повні"), JSON_UNESCAPED_UNICODE);
}
?>