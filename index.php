<?php

declare(strict_types=1);

require_once "bootstrap.php";

use App\Controller\BookController;
use App\Model\{Database, Book};

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


parse_str($_SERVER['QUERY_STRING'], $params);
$requestMethod = $_SERVER["REQUEST_METHOD"];

$id = null;
if (in_array($requestMethod, [BookController::METHOD_PUT, BookController::METHOD_GET]
    ) && isset($params['book_id']) && is_numeric($params['book_id'])) {
    $id = (int)$params['book_id'];
}


$db = new Database();
$conn = $db->getConnection();
$bookController = new BookController($conn, $requestMethod, $id);
$bookController->processApiRequest();
