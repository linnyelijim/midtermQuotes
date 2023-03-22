<?php
//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
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

//Set id accordingly
$authors->id = $data->id;

//Messages and array for responses
$no_parameters = array('message' => 'Missing Required Parameters');
$no_author = array('message' => 'author_id Not Found');
$deleted_author = array('id' => $authors->id);

//Validate required parameters
if (!isset($data->id)) {
    echo json_encode($no_parameters);
}

//If delete fails, no author else return deleted author id
if (!$authors->delete()) {
    echo json_encode($no_author);
} else {
    echo json_encode($deleted_author);
}
?>