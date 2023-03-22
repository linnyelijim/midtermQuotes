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

//Messages and array for response
$no_parameters = array('message' => 'Missing Required Parameters');
$no_author = array('message' => 'author_id Not Found');
$new_author = array('id' => $authors->id, 'author' => $authors->author);
$not_updated = array('message' => 'Authors Not Updated');

//Validates required parameters
if (!isset($data->author)) {
	echo json_encode($no_parameters);
	exit();
}

//Sets id and category accordingly
$authors->id = $data->id;
$authors->author = $data->author;

//If update fails ouput no author, else if succeeds output new author, else not updated
if (!$authors->update()) {
	echo json_encode($no_author);
	exit();

} else if ($authors->update()) {
	echo json_encode($new_author);

} else {
	echo json_encode($not_updated);
}
?>