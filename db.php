<?php
$servername = "sci-mysql";
$username = "coa123wuser";
$password = "grt64dkh!@2FD";
$database_name = "coa123wdb";

try {
	$conn = new PDO("mysql:host=$servername;dbname=$database_name",
	$username, $password);
	// set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
}
?>
