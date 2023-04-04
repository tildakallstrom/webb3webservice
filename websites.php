<?php
require 'includes/config.php';
require 'includes/Database.php';
require 'classes/Website.class.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

$method = $_SERVER['REQUEST_METHOD'];
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$websites = new Website();

switch ($method) {
    case 'GET':
        http_response_code(200);
        $response = $websites->getWebsites();
        if (count($response) == 0) {
            $response = array("message" => "Inga webbplatser funna.");
        }
        if (isset($id)) {
            $result = $websites->getWebsiteById($id);
        } else {
            $result = $websites->getWebsites();
        }
        if (sizeof($result) > 0) {
            http_response_code(200); 
        } else {
            http_response_code(404);
            $result = array("message" => "Inga Webbplatser funna.");
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        if ($data->title == "" || $data->description == "" || $data->url == "" || $data->image == "") {
            $response = array("message" => "Fyll i ordentligt va!");
            http_response_code(400); 
        } else {
            if ($websites->createWebsite($data->title, $data->description, $data->url, $data->image)) {
                $response = array("message" => "Webbplats har skapats.");
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
            $result = array("message" => "No id is sent");
        } else {
            $data = json_decode(file_get_contents("php://input"));
            if ($websites->updateWebsite($id, $data)) {
                http_response_code(200);
                $result = array("message" => "Website updated");
            } else {
                http_response_code(503);
                $result = array("message" => "No id is sent");
            }
        }
        break;
    case 'DELETE':
        if (!isset($id)) {
            http_response_code(510); 
            $result = array("message" => "Inget id har skickats.");
        } else {
            if ($websites->deleteWebsite($id)) {
                http_response_code(200); 
                $result = array("message" => "Webbplatsen är raderad.");
            } else {
                http_response_code(503); 
                $result = array("message" => "Webbplatsen är inte raderad.");
            }
        }
        break;
}

echo json_encode($result);