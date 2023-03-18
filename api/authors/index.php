<?php
//Keeps CORS from blocking automated tests
header('Access-Control-Allow-Origin: *'); //Response can be shared with origin's requesting code
header('Content-Type: application/json'); //Tells browser intended data type 
$method = $_SERVER['REQUEST_METHOD']; //Gets HTTP request value


if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE'); //Permitted methods
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With'); //Specifies what has access
    exit();
}


if ($method === 'GET') {
    //Checking for URL parameter, like id=1
    if (isset($_GET['id'])) {
        require('read_single.php'); //Read only specified
    } else {
        require('read.php'); //No parameter, read all 
    }
} else if ($method === 'POST') {
    require('create.php');
} else if ($method === 'PUT') {
    require('update.php');
} else if ($method === 'DELETE') {
    require('delete.php');
}
?>