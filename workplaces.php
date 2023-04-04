<?php
require 'includes/config.php';
require 'includes/Database.php';
require 'classes/Workplace.class.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

$method = $_SERVER['REQUEST_METHOD'];
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$works = new Workplace();

switch ($method) {
    case 'GET':
        http_response_code(200);
        $response = $works->getWorkplaces();
        if (count($response) == 0) {
            $response = array("message" => "Inga arbeten funna.");
        }
        if (isset($id)) {
            $result = $works->getWorkplaceById($id);
        } else {
            $result = $works->getWorkplaces();
        }
        if (sizeof($result) > 0) {
            http_response_code(200); 
        } else {
            http_response_code(404); 
            $result = array("message" => "Inga arbeten funna.");
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if ($data->workplace == "" || $data->title == "" || $data->city == "" || $data->country == "" || $data->description == "" || $data->start == "" || $data->stop == "") {
            $response = array("message" => "Fyll i ordentligt va!");
            http_response_code(400); 
        } else {
            if ($works->createWork($data->workplace, $data->title, $data->city, $data->country, $data->description, $data->start, $data->stop)) {
                $response = array("message" => "Arbete har skapats.");
                http_response_code(201); 
            } else {
                $response = array("message" => "Något gick fel, försök igen.");
                http_response_code(500); 
            }
        }
        break;
    case 'PUT':
        if (!isset($id)) {
            http_response_code(510); 
            $result = array("message" => "Inget id har skickats");
        } else {
            $data = json_decode(file_get_contents("php://input"));
            if ($works->updateWork($id, $data)) {
                http_response_code(200);
                $result = array("message" => "Arbetet har uppdaterats");
            } else {
                http_response_code(503);
                $result = array("message" => "Inget id har skickas");
            }
        }
        break;
    case 'DELETE':
        if (!isset($id)) {
            http_response_code(510); 
            $result = array("message" => "Inget id har skickats.");
        } else {
            if ($works->deleteWork($id)) {
                http_response_code(200); 
                $result = array("message" => "Arbetet har raderats.");
            } else {
                http_response_code(503); 
                $result = array("message" => "Arbetet har inte raderats.");
            }
        }
        break;
}

echo json_encode($result);