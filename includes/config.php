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
      
   // Db settings (remote - studenter.miun.se)
   define("DBHOST", "studentmysql.miun.se");
   define("DBUSER", "tika1900");
   define("DBPASS", "y8rwy9QKTW");
   define("DBDATABASE", "tika1900");
      
} /*else {
          //db-inställningar
    define("DBHOST", "localhost");
    define("DBUSER", "course");
    define("DBPASS", "course");
    define("DBDATABASE", "course");
}
*/