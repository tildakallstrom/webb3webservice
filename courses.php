<?php
require 'includes/config.php';
require 'includes/Database.php';
require 'classes/Course.class.php';


//Headers som gör webbtjänsten tillgänglig från alla domäner
//Gör att webbtjänsten går att komma åt från alla domäner (asterisk * betyder alla)
header('Access-Control-Allow-Origin: *');

//Talar om att webbtjänsten skickar data i JSON-format
header('Content-Type: application/json');

//Vilka metoder som webbtjänsten accepterar, som standard tillåts bara GET.
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');

//Vilka headers som är tillåtna vid anrop från klient-sidan, kan bli problem med CORS (Cross-Origin Resource Sharing) utan denna.
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');


$method = $_SERVER['REQUEST_METHOD'];
//om en parameter av id finns i urlen
if(isset($_GET['id'])) {
    $id = $_GET['id'];
}


//skapar instans av klassen för att skicka SQL-fråga till db
//skickar med db anslutning som parameter
$courses = new Course();

switch($method) {
    case 'GET':
        //http response status code
        http_response_code(200);

        $response = $courses->getCourses();
        if(count($response) == 0) {
            $response = array("message" => "Inga kurser funna.");
        }

        if(isset($id)) {
            //kör funktion för att läsa rad med specifikt id
            $result = $courses->getCourseById($id);
        } else {
            //kör funktion för att läsa data från tabellen
            $result = $courses->getCourses();
        }

        //kontrollera om resultatet innehåller ngn rad
        if(sizeof($result) > 0) {
            http_response_code(200); //ok
        } else {
            http_response_code(404); //ej funnen
            $result = array("message" => "Inga kurser funna.");
        }
        break;
        case 'POST':
            $data = json_decode(file_get_contents("php://input"));

            if($data->coursename == "" || $data->school == "" || $data->start == "" || $data->stop == "") {
    $response = array("message" => "Fyll i ordentligt va!");

    http_response_code(400); //user error
} else {
    if($courses->create($data->coursename, $data->school, $data->start, $data->stop)) {
        $response = array("message" => "Kurs har skapats.");

        http_response_code(201); //created
    } else {
        $response = array("message" => "Något gick fel, försök igen.");

        http_response_code(500); //server error
    }
}
            break;
            case 'PUT':
                //Om inget id är med skickat, skicka felmeddelande
                if(!isset($id)) {
                    http_response_code(510);//400); //Bad Request - The server could not understand the request due to invalid syntax.
                    $result = array("message" => "No id is sent");
                //Om id är skickad   
                } else {
                    $data = json_decode(file_get_contents("php://input"));
        
             

                  if($courses->updateCourse($id, $data)) {
                         http_response_code(200);
                    $result = array("message" => "Course updated");
                  } else{
                      http_response_code(503);
                      $result = array ("message" => "No id is sent");
                  }
                 
                    }
            break;
            case 'DELETE':
                //om id inte är medskickat, skicka error
                if(!isset($id)) {
                    http_response_code(510); //not extended
                    $result = array("message" => "Inget id har skickats.");
                    //om id har skickats
                } else {
                    //kör funktion för att radera en rad
                    if($courses->deleteCourse($id)) {
                        http_response_code(200); //ok
                        $result = array("message" => "Kursen är raderad.");
                    } else {
                        http_response_code(503); //server error
                        $result = array("message" => "Kursen är inte raderad.");
                    }
                }
                break;
}

//returnera resultat som JSON
echo json_encode($result);


