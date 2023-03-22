<?php
//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

//Include files
include_once '../../config/Database.php';
include_once '../../models/Category.php';

//Instantiate database and connect
$database = new Database();
$db = $database->connect();

//Instantiate cateogry object
$categories = new Categories($db);

//Get raw data
$data = json_decode(file_get_contents("php://input"));

//Messages and array for response
$no_parameters = array('message' => 'Missing Required Parameters');
$no_category = array('message' => 'category_id Not Found');
$not_updated = array('message' => 'Category Not updated');
$new_category = array('id' => $categories->id, 'category' => $categories->category);

//Validates required parameters
if (!isset($data->category)) {
    echo json_encode($no_parameters);
    exit();
}

//Sets id and category accordingly
$categories->id = $data->id;
$categories->category = $data->category;

//If update fails ouput no category, else if succeeds output new category, else not updated
if (!$categories->update()) {
    echo json_encode($no_category);
    exit();

} else if ($categories->update()) {
    echo json_encode($new_category);

} else {
    echo json_encode($not_updated);
}
?>