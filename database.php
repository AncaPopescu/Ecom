<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

require_once $_SERVER['DOCUMENT_ROOT'].'/ecomerce/config.php';
require_once BASEURL.'/help/help.php';

?>
