<?php
require 'includes/config.php';
require 'includes/Database.php';
require 'classes/Course.class.php';

header('Access-Control-Allow-Origin: *');

header('Content-Type: application/json');

header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');

header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

$method = $_SERVER['REQUEST_METHOD'];
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$courses = new Course();

switch ($method) {
    case 'GET':
        http_response_code(200);

        $response = $courses->getCourses();
        if (count($response) == 0) {
            $response = array("message" => "Inga kurser funna.");
        }

        if (isset($id)) {
            $result = $courses->getCourseById($id);
        } else {
            $result = $courses->getCourses();
        }

        if (sizeof($result) > 0) {
            http_response_code(200); 
        } else {
            http_response_code(404); 
            $result = array("message" => "Inga kurser funna.");
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        if ($data->coursename == "" || $data->school == "" || $data->start == "" || $data->stop == "") {
            $response = array("message" => "Fyll i ordentligt va!");

            http_response_code(400); 
        } else {
            if ($courses->create($data->coursename, $data->school, $data->start, $data->stop)) {
                $response = array("message" => "Kurs har skapats.");

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

            if ($courses->updateCourse($id, $data)) {
                http_response_code(200);
                $result = array("message" => "Course updated");
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
            if ($courses->deleteCourse($id)) {
                http_response_code(200);
                $result = array("message" => "Kursen är raderad.");
            } else {
                http_response_code(503); 
                $result = array("message" => "Kursen är inte raderad.");
            }
        }
        break;
}
echo json_encode($result);