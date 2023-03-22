<?php
//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

//Include files
include_once '../../config/Database.php';
include_once '../../models/Author.php';

//Instantiate database and connect
$database = new Database();
$db = $database->connect();

//Instantiate author object
$authors = new Authors($db);

//Get raw data
$data = json_decode(file_get_contents("php://input"));

//Messages and array for responses
$no_parameters = array('message' => 'Missing Required Parameters');
$new_author = array('id' => $authors->id, 'author' => $authors->author);
$not_created = array('message' => 'Author Not Created');

//Validates required parameters
if (!isset($data->author)) {
    echo json_encode($no_parameters);
    exit();
}

//Sets author and variables accordingly
$authors->author = $data->author;
$created = $authors->create();

//If created, output new author else output failure statement
if ($created) {
    echo json_encode($new_author);
} else {
    echo json_encode($not_created);
}
?>