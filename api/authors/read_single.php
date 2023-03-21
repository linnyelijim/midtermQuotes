<?php
//Headers
header('Content-Type: application/json');

//Include files
include_once '../../config/Database.php';
include_once '../../models/Author.php';
include_once '../../functions/isValid.php';

//Instantiate database and connect
$database = new Database();
$db = $database->connect();

//Instantiate author object
$authors = new Authors($db);

//Get id
$authors->id = isset($_GET['id']) ? $_GET['id'] : die();

//Message response
$no_author = ['message' => 'author_id Not Found'];

//Validates author exists
if (!isValid($authors->id, $authors)) {
    echo json_encode($no_author);
    exit();
}

//Calls to authors to read author with specified id
$authors->read_single();

//Array for output
$author_arr = array(
    'id' => $authors->id,
    'author' => $authors->author
);

//Output array
echo json_encode($author_arr);
?>