<?php
include("includes/config.php");

$db = new mysqli(DBHOST, DBUSER, DBPASS, DBDATABASE);
if ($db->connect_errno > 0) {
    die('Fel vid anslutning [' . $db->connect_error . ']');
}

$sql = "DROP TABLE IF EXISTS courses;
CREATE TABLE `courses` (
    `id` int(2) PRIMARY KEY AUTO_INCREMENT,
    `coursename` varchar(50) NOT NULL,
    `school` varchar(50) NOT NULL,
    `start` DATE NOT NULL,
    `stop` DATE NOT NULL,
    `created` timestamp NOT NULL DEFAULT current_timestamp()
  );";

echo "<pre>$sql</pre>";

if ($db->multi_query($sql)) {
    echo "<p>Tabeller installerades.</p>";
} else {
    echo "<p class='error'>Fel vid installation av tabeller.</p>";
}