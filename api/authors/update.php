<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Author.php';

//Instantiate DB & connect
$database = new Database();
$db = $database->connect();

//Instantiate blog post object
$authors = new Authors($db);

//Raw posted data
$data = json_decode(file_get_contents("php://input"));

$no_parameters = ['message' => 'Missing Required Parameters'];
$no_author = ['message' => 'author_id Not Found'];
$new_author = array('id' => $authors->id, 'author' => $authors->author);
$not_updated = ['message' => 'Authors Not Updated'];

if (!isset($data->author)) {
	echo json_encode($no_parameters);
	exit();
}

$authors->id = $data->id;
$authors->author = $data->author;

if (!$authors->update()) {
	echo json_encode($no_author);
	exit();

} else if ($authors->update()) {
	echo json_encode($new_author);

} else {
	echo json_encode($not_updated);
}
?>