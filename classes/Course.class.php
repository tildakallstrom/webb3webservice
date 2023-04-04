<?php
class Course
{
    private $db;
    private $coursename;
    private $school;
    private $start;
    private $stop;

    public function __construct()
    {
        $this->db = new mysqli(DBHOST, DBUSER, DBPASS, DBDATABASE);
        if ($this->db->connect_error) {
            die("Fel vid anslutning: " . $this->db->connect_error);
        }
    }

    public function getCourses(): array
    {
        $sql = "SELECT * FROM courses ORDER BY id;";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCourseById(int $id): array
    {
        $id = intval($id);
        $sql = "SELECT * FROM courses WHERE id = $id;";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function create(string $coursename, string $school, string $start, string $stop): bool
    {
        if (!$this->setCoursename($coursename)) {
            return false;
        }
        if (!$this->setSchool($school)) {
            return false;
        }
        $this->start = $start;
        $this->stop = $stop;

        $stmt = $this->db->prepare("INSERT INTO courses (coursename, school, start, stop) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $this->coursename, $this->school, $this->start, $this->stop);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
        $stmt->close();
    }

    public function deleteCourse(int $id): bool
    {
        $id = intval($id);
        $sql = "DELETE FROM courses WHERE id=$id;";
        $result = $this->db->query($sql);

        return $result;
    }

    public function updateCourse($id, $data): bool
    {
        $id = intval($id);

        $coursename = $data->coursename;
        $school = $data->school;
        $start = $data->start;
        $stop = $data->stop;

        $sql = "UPDATE courses SET coursename= '$coursename', school= '$school', start= '$start', stop= '$stop' WHERE id=$id;";
        $result = mysqli_query($this->db, $sql);
        return $result;
    }

    public function setCoursename($coursename)
    {
        if (filter_var($coursename)) {
            $coursename = strip_tags(html_entity_decode($coursename), '<p><a><br><i><b><strong><em>');

            $this->coursename = $this->db->real_escape_string($coursename);
            return true;
        } else {
            return false;
        }
    }
    
    public function setSchool($school)
    {
        if (filter_var($school)) {
            $school = strip_tags(html_entity_decode($school), '<p><a><br><i><b><strong><em>');

            $this->school = $this->db->real_escape_string($school);
            return true;
        } else {
            return false;
        }
    }
}
