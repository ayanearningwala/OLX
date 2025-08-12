<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$servername = "localhost"; // Assuming localhost, change it if you have a different host
$username = "ukoh9qzoemwgg";
$password = "sljwrufhuykd";
$dbname = "dbqqxnov8eb9av";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    // echo "Connected successfully!";
}
?>
