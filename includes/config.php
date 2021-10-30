<?php
//Tilda Källström 2021 Webbutveckling 3 Mittuniversitetet
 
$devMode = true;

if($devMode) {
    //aktivera felrapportering
    error_reporting(-1);
    ini_set("display_errors", 1);
}

//ladda in klasser
spl_autoload_register(function ($class_name) {
    include 'classes/' . $class_name . '.class.php';
});

if($devMode) {
      
   // Db settings x
   define("DBHOST", "x");
   define("DBUSER", "x");
   define("DBPASS", "x");
   define("DBDATABASE", "x");
      
} 
