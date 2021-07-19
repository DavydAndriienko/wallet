<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// подключение базы данных и файл, содержащий объекты 
include_once '../config/database.php';
include_once '../objects/transaction.php';

$database = new Database();
$db = $database->getConnection();

$transaction = new transaction($db);

$transaction->user_id = isset($_GET['id']) ? $_GET['id'] : die();

$stmt = $transaction->read();

$num = mysqli_num_rows($stmt);

if ($num>0) {

    // массив товаров 
    $transaction_arr=array();
    $transaction_arr["records"]=array();

    // получаем содержимое нашей таблицы 
    // fetch() быстрее, чем fetchAll() 
    while ($row = mysqli_fetch_row($stmt)){

        // извлекаем строку 
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

    // устанавливаем код ответа - 200 OK 
    http_response_code(200);

    // выводим данные о товаре в формате JSON 
    echo json_encode($transaction_arr);
}

else {

    // установим код ответа - 404 Не найдено 
    http_response_code(404);

    // сообщаем пользователю, что товары не найдены 
    echo json_encode(array("message" => "Транзакцій не знайдено"), JSON_UNESCAPED_UNICODE);
}