<?php
include("includes/config.php");

$db = new mysqli(DBHOST, DBUSER, DBPASS, DBDATABASE);
if ($db->connect_errno > 0) {
    die('Fel vid anslutning [' . $db->connect_error . ']');
}

$sql = "DROP TABLE IF EXISTS works;
CREATE TABLE `works` (
    `id` int(2) NOT NULL,
    `workplace` varchar(50) NOT NULL,
    `title` varchar(50) NOT NULL,
    `city` varchar(100) NOT NULL,
    `country` varchar(100) NOT NULL,
    `description` text NOT NULL,
    `start` date NOT NULL,
    `stop` date NOT NULL,
    `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);";

echo "<pre>$sql</pre>";

if ($db->multi_query($sql)) {
    echo "<p>Tabeller installerades.</p>";
} else {
    echo "<p class='error'>Fel vid installation av tabeller.</p>";
}