<?php
//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

//Include files
include_once '../../config/Database.php';
include_once '../../models/Quotes.php';
include_once '../../functions/isValid.php';

//Instantiate database and connect
$database = new Database();
$db = $database->connect();

//Instantiate quotes object
$quotes = new Quotes($db);

//Get raw data
$data = json_decode(file_get_contents("php://input"));

//Messages and array for responses
$no_parameters = array('message' => 'Missing Required Parameters');
$no_author = array('message' => 'author_id Not Found');
$no_category = array('message' => 'category_id Not Found');
$not_created = array('message' => 'Quotes Not Created');

//Validates required parameters
if (!isset($data->quote) || !isset($data->author_id) || !isset($data->category_id)) {
    echo json_encode($no_parameters);
    exit();
}


//Sets quote, author_id, and category_id accordingly
$quotes->quote = $data->quote;
$quotes->author_id = $data->author_id;
$quotes->category_id = $data->category_id;

//Validates author exists
if (!isValid($quotes->author_id, $quotes)) {
    echo json_encode($no_author);
    exit();
}

//Validates category exists
if (!isValid($quotes->category_id, $quotes)) {
    echo json_encode($no_category);
    exit();
}

$new_quote = array('id' => $quotes->id, 'quote' => $quotes->quote, 'author_id' => $quotes->author_id, 'category_id' => $quotes->category_id);

//If created, output new quote else output failure statement
if ($quotes->create()) {
    echo json_encode($new_quote);
} else {
    echo json_encode($not_created);
}
?>