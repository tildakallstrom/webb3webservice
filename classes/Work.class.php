<?php
//Tilda Källström 2021 Webbutveckling 3 Mittuniversitetet
class Work {
    //properties
    private $db;
    private $workplace;
    private $title;
    private $city;
    private $start;
    private $stop;

 

    //metoder
    public function __construct() {
        //connect to db
        $this->db = new mysqli(DBHOST, DBUSER, DBPASS, DBDATABASE);
        //check connection
        if ($this->db->connect_error) {
            die("Fel vid anslutning: " . $this->db->connect_error);
        }
    } 

    //plocka fram alla kurser
    public function getWorks(): array {
        $sql = "SELECT * FROM works ORDER BY id;";
        $result = $this->db->query($sql);
        //return associative array
        return $result->fetch_all(MYSQLI_ASSOC);
    }
//plocka ut en kurs
    public function getWorkById(int $id) : array {
        $id = intval($id);
        $sql = "SELECT * FROM works WHERE id = $id;";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
//lägga till en kurs
    public function createWork(string $workplace, string $title, string $start, string $stop) :bool {
        if (!$this->setWorkplace($workplace)) {
            return false;
        }
        if (!$this->setTitle($title)) {
            return false;
        }
      
            $this->start = $start;
            $this->stop = $stop;

            $stmt = $this->db->prepare("INSERT INTO works (workplace, title, start, stop) VALUES (?, ?, ?, ?);");
            $stmt->bind_param("ssss", $this->workplace, $this->title, $this->start, $this->stop);
       
            //execute
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
            $stmt->close();
        }

//radera kurs
    public function deleteWork(int $id) : bool {
        $id = intval($id);
            $sql = "DELETE FROM works WHERE id=$id;";
            $result = $this->db->query($sql);

            return $result;
    }

//uppdatera kurs
public function updateWork($id, $data) : bool {
    $id = intval($id);

    $workplace = $data->workplace;
     $title = $data->title;
    
      $start = $data->start;
        $stop = $data->stop;
        
        $sql = "UPDATE works SET workplace= '$workplace', title= '$title', start= '$start', stop= '$stop' WHERE id=$id;";
        $result = mysqli_query($this->db, $sql);
return $result;
}

//setters
public function setWorkplace($workplace){
    if (filter_var($workplace)) {
        $workplace = strip_tags(html_entity_decode($workplace), '<p><a><br><i><b><strong><em>');

        $this->workplace = $this->db->real_escape_string($workplace);
        return true;
    } else {
        return false;
    }
}
public function setTitle($title){
    if (filter_var($title)) {
        $title = strip_tags(html_entity_decode($title), '<p><a><br><i><b><strong><em>');

        $this->title = $this->db->real_escape_string($title);
        return true;
    } else {
        return false;
    }
}


} 