<?php
//Tilda Källström 2021 Webbutveckling 3 Mittuniversitetet
class Website {
    //properties
    private $db;
    private $title;
    private $description;
    private $url;
    private $image;

 

    //metoder
    public function __construct() {
        //connect to db
        $this->db = new mysqli(DBHOST, DBUSER, DBPASS, DBDATABASE);
        //check connection
        if ($this->db->connect_error) {
            die("Fel vid anslutning: " . $this->db->connect_error);
        }
    } 

    //plocka fram alla webbplatser
    public function getWebsites(): array {
        $sql = "SELECT * FROM websites ORDER BY id;";
        $result = $this->db->query($sql);
        //return associative array
        return $result->fetch_all(MYSQLI_ASSOC);
    }
//plocka ut en webbplats
    public function getWebsiteById(int $id) : array {
        $id = intval($id);
        $sql = "SELECT * FROM websites WHERE id = $id;";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
//lägga till en webbplats
    public function createWebsite(string $title, string $description, string $url, string $image) :bool {
        if (!$this->setTitle($title)) {
            return false;
        }
        if (!$this->setDescription($description)) {
            return false;
        }
        if (!$this->setUrl($url)) {
            return false;
        }
        if (!$this->setImage($image)) {
            return false;
        }

            $stmt = $this->db->prepare("INSERT INTO websites (title, description, url, image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $this->title, $this->description, $this->url, $this->image);
       
            //execute
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
            $stmt->close();

            
        }

//radera webbplats
    public function deleteWebsite(int $id) : bool {
        $id = intval($id);
            $sql = "DELETE FROM websites WHERE id=$id;";
            $result = $this->db->query($sql);

            return $result;
    }

//uppdatera webbplats
public function updateWebsite($id, $data) : bool {
    $id = intval($id);
 
    $title = $data->title;
    $description = $data->description;
      $url = $data->url;
        $image = $data->image;
        
        $sql = "UPDATE websites SET title= '$title', description= '$description', url= '$url', image= '$image' WHERE id=$id;";
        $result = mysqli_query($this->db, $sql);
return $result;
}


//setters
public function setTitle($title) {
    if (filter_var($title)) {
       $title = strip_tags(html_entity_decode($title), '<p><a><br><i><b><strong><em>');
        $this->title = $this->db->real_escape_string($title);
        //$this->title = $this->db->real_escape_string($title);
        return true;
    } else {
        return false;
    }
}


public function setDescription($description){
    if (filter_var($description)) {
        $description = strip_tags(html_entity_decode($description), '<p><li><ol><a><br><i><b><strong><em>');

        $this->description = $this->db->real_escape_string($description);
        return true;
    } else {
        return false;
    }
}

public function setUrl($url) {
    if (filter_var($url)) {
        $url = strip_tags(html_entity_decode($url), '<span>');

        $this->url = $this->db->real_escape_string($url);
        return true;
    } else {
        return false;
    }
}

public function setImage($image) {
    if (filter_var($image)) {
        $image = strip_tags(html_entity_decode($image), '<span>');

        $this->image = $this->db->real_escape_string($image);
        return true;
    } else {
        return false;
    }
}

}
