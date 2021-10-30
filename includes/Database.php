<?php

class Database {
    //inställningar för db
    private $host = "x";
    private $db_name = "x";
    private $password = "x";
    private $username = "x";
    private $db;
 


//anslutningar till db
public function connect() {
    $this->db = null;

    try {
        $this->db = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //fel med anslutning
    } catch(PDOException $e) {
        echo "Connection Error " . $e->getMessage();
    }
    return $this->db;
}
public function close() {
    $this->db = null;
}
}
