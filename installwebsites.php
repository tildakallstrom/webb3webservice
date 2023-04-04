<?php
include("includes/config.php");
$db = new mysqli(DBHOST, DBUSER, DBPASS, DBDATABASE);
if ($db->connect_errno > 0) {
    die('Fel vid anslutning [' . $db->connect_error . ']');
}

$sql = "DROP TABLE IF EXISTS websites;
CREATE TABLE websites(
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(64) NOT NULL,
    description text NOT NULL,
    url text NOT NULL,
    image VARCHAR(128) DEFAULT 'empty.jpg',
    created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    );";

echo "<pre>$sql</pre>";

if ($db->multi_query($sql)) {
    echo "DB installerad.";
} else {
    echo "Fel vid installation av DB.";
}