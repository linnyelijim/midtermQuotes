<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

$database = new Database();
$db = $database->connect();

$categories = new Categories($db);

$data = json_decode(file_get_contents("php://input"));

$no_parameters = ['message' => 'Missing Required Parameters'];
$no_category = ['message' => 'category_id Not Found'];
$not_updated = ['message' => 'Category Not updated'];
$new_category = array('id' => $categories->id, 'category' => $categories->category);

if (!isset($data->category)) {
    echo json_encode($no_parameters);
    exit();
}

$categories->id = $data->id;
$categories->category = $data->category;

if (!$categories->update()) {
    echo json_encode($no_category);
    exit();

} else if ($categories->update()) {
    echo json_encode($new_category);

} else {
    echo json_encode($not_updated);
}
?>