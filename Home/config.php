<?php
require_once '/Applications/XAMPP/xamppfiles/htdocs/vendor/autoload.php';
require_once 'mail_credential.php';
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '7318');
define('DB_NAME', 'user_database');
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
// Check connection
if ($link === false) {
    die("ERROR: Could not connect. Please make sure that you run database.sql. You may also need to update config.php file: " . mysqli_connect_error());
}
?>
