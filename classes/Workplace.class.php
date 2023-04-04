<?php
class Workplace
{
    private $db;
    private $workplace;
    private $title;
    private $city;
    private $country;
    private $description;
    private $start;
    private $stop;

    public function __construct()
    {
        $this->db = new mysqli(DBHOST, DBUSER, DBPASS, DBDATABASE);
        if ($this->db->connect_error) {
            die("Fel vid anslutning: " . $this->db->connect_error);
        }
    }

    public function getWorkplaces(): array
    {
        $sql = "SELECT * FROM workplaces;";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getWorkplaceById(int $id): array
    {
        $id = intval($id);
        $sql = "SELECT * FROM workplaces WHERE id = $id;";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function createWork(string $workplace, string $title, string $city, string $country, string $description, string $start, string $stop): bool
    {
        if (!$this->setWorkplace($workplace)) {
            return false;
        }
        if (!$this->setTitle($title)) {
            return false;
        }
        if (!$this->setCity($city)) {
            return false;
        }
        if (!$this->setCountry($country)) {
            return false;
        }
        if (!$this->setDescription($description)) {
            return false;
        }
        $this->start = $start;
        $this->stop = $stop;

        $stmt = $this->db->prepare("INSERT INTO workplaces (workplace, title, city, country, description, start, stop) VALUES (?, ?, ?, ?, ?, ?, ?);");
        $stmt->bind_param("sssssss", $this->workplace, $this->title, $this->city, $this->country, $this->description, $this->start, $this->stop);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
        $stmt->close();
    }

    public function deleteWork(int $id): bool
    {
        $id = intval($id);
        $sql = "DELETE FROM workplaces WHERE id=$id;";
        $result = $this->db->query($sql);

        return $result;
    }

    public function updateWork($id, $data): bool
    {
        $id = intval($id);

        $workplace = $data->workplace;
        $title = $data->title;
        $city = $data->city;
        $country = $data->country;
        $description = $data->description;
        $start = $data->start;
        $stop = $data->stop;

        $sql = "UPDATE workplaces SET workplace= '$workplace', title= '$title', city= '$city', country= '$country', description= '$description', start= '$start', stop= '$stop' WHERE id=$id;";
        $result = mysqli_query($this->db, $sql);
        return $result;
    }

    public function setWorkplace($workplace)
    {
        if (filter_var($workplace)) {
            $workplace = strip_tags(html_entity_decode($workplace), '<p><a><br><i><b><strong><em>');

            $this->workplace = $this->db->real_escape_string($workplace);
            return true;
        } else {
            return false;
        }
    }

    public function setTitle($title)
    {
        if (filter_var($title)) {
            $title = strip_tags(html_entity_decode($title), '<p><a><br><i><b><strong><em>');

            $this->title = $this->db->real_escape_string($title);
            return true;
        } else {
            return false;
        }
    }

    public function setCity($city)
    {
        if (filter_var($city)) {
            $city = strip_tags(html_entity_decode($city), '<p><a><br><i><b><strong><em>');

            $this->city = $this->db->real_escape_string($city);
            return true;
        } else {
            return false;
        }
    }

    public function setCountry($country)
    {
        if (filter_var($country)) {
            $country = strip_tags(html_entity_decode($country), '<p><a><br><i><b><strong><em>');

            $this->country = $this->db->real_escape_string($country);
            return true;
        } else {
            return false;
        }
    }
    
    public function setDescription($description)
    {
        if (filter_var($description)) {
            $description = strip_tags(html_entity_decode($description), '<p><a><br><i><b><strong><em>');

            $this->description = $this->db->real_escape_string($description);
            return true;
        } else {
            return false;
        }
    }
}
