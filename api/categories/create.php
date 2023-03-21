<?php
//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

//Include files
include_once '../../config/Database.php';
include_once '../../models/Category.php';

//Instantiate database and connect
$database = new Database();
$db = $database->connect();

//Instantiate category object
$categories = new Categories($db);

//Get raw data
$data = json_decode(file_get_contents("php://input"));

//Messages and array for responses
$no_parameters = ['message' => 'Missing Required Parameters'];
$new_category = array('id' => $categories->id, 'category' => $categories->category);
$not_created = ['message' => 'Category Not Created'];

//Validates required parameters
if (!isset($data->category)) {
    echo json_encode($no_parameters);
    exit();
}

//Sets category and variables accordingly
$categories->category = $data->category;
$created = $categories->create();

//If created, output new category else output failure statement
if ($created) {
    echo json_encode($new_category);
} else {
    echo json_encode($not_created);
}
?>