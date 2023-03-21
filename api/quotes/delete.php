<?php
//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

//Include files
include_once '../../config/Database.php';
include_once '../../models/Quotes.php';

//Instantiate database and connect
$database = new Database();
$db = $database->connect();

//Instantiate quote object
$quotes = new Quotes($db);

//Get raw data
$data = json_decode(file_get_contents("php://input"));

//Set id accordingly
$quotes->id = $data->id;

//Messages and array for responses
$no_parameters = ['message' => 'Missing Required Parameters'];
$no_quotes = ['message' => 'No Quotes Found'];
$deleted_quote = array('id' => $quotes->id);

//Validate required parameters
if (!isset($data->id)) {
    echo json_encode($no_parameters);
    exit();
}

//If delete fails, no category else return deleted category id
if (!$quotes->delete()) {
    echo json_encode($no_quotes);
    exit();
} else {
    echo json_encode($deleted_quote);
}
?>