<?php


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../config/database.php';

// создание объекта товара 
include_once '../objects/user.php';

$data = json_decode(file_get_contents("php://input"));


if (
        !empty($data->user_id) &&
        !empty($data->day_limit)
) {
    
    $database = new Database();
    $db = $database->getConnection();

    $user = new user($db);
    if($user->create($data->user_id, $data->day_limit)){
        http_response_code(201);
        echo json_encode(array("message" => "Користувач успішно доданий"), JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(501);
        echo json_encode(array("message" => "Неможливо додати користувача, користувач з таким логіном вже існує"), JSON_UNESCAPED_UNICODE);
    }
} else {
    http_response_code(400);

    echo json_encode(array("message" => "Неможливо додати користувача, данні не повні"), JSON_UNESCAPED_UNICODE);
}
?>