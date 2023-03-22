<?php
//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//Include files
include_once '../../config/Database.php';
include_once '../../models/Category.php';

//Instantiate database and connect
$database = new Database();
$db = $database->connect();

//Instantiate category object
$categories = new Categories($db);

//Declares variables to store categories and count rows
$result = $categories->read_categories();
$num = $result->rowCount();

//Message for response
$no_category = array('message' => 'category_id Not Found');

//Creates array of categories, ouputs array else no category
if ($num > 0) {
    $category_arr = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $category_item = array(
            'id' => $id,
            'category' => $category
        );
        array_push($category_arr, $category_item);
    }
    echo json_encode($category_arr);
} else {
    echo json_encode($no_category);
}
?>