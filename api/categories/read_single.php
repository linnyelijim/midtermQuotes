<?php
//Headers
header('Content-Type: application/json');

//Include files
include_once '../../config/Database.php';
include_once '../../models/Category.php';
include_once '../../functions/isValid.php';

//Instantiate database and connect
$database = new Database();
$db = $database->connect();

//Instantiate category object
$categories = new Categories($db);

//Get id
$categories->id = isset($_GET['id']) ? $_GET['id'] : die();

//Message response
$no_category = ['message' => 'category_id Not Found'];

//Validates category exists
if (!isValid($categories->id, $categories)) {
    echo json_encode($no_category);
    exit();
}

//Calls to categories to read category with specified id
$categories->read_single();

//Array for output
$category_arr = array(
    'id' => $categories->id,
    'category' => $categories->category
);

//Output array
echo json_encode($category_arr);
?>