<?php
$devMode = true;

if ($devMode) {
    error_reporting(-1);
    ini_set("display_errors", 1);
}

spl_autoload_register(function ($class_name) {
    include 'classes/' . $class_name . '.class.php';
});

if ($devMode) {
    define("DBHOST", "x");
    define("DBUSER", "x");
    define("DBPASS", "x");
    define("DBDATABASE", "x");
}
