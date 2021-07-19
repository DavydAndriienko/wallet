<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new user($db);

$user->id = isset($_GET['id']) ? $_GET['id'] : die();

$stmt = $user->get_balance();

$num = mysqli_num_rows($stmt);

if ($num>0) {

    while ($row = mysqli_fetch_row($stmt)){

        // извлекаем строку 
        $user_item=array(
            "id" => $row[0],
            "balance" => $row[1],
        );

    }

    http_response_code(200);

    echo json_encode($user_item);
}

else {
    http_response_code(404);

    echo json_encode(array("message" => "Користувача не знайденно"), JSON_UNESCAPED_UNICODE);
}