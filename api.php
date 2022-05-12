<?php
include_once "db.php";

function query(String $sql) {
	global $conn;
	$result = $conn->query($sql);
	return $result->fetchAll(PDO::FETCH_ASSOC);
	// $data = array();
	// while ($row = $result->fetch()) {
	// 	$data[] = $row;
	// }
	// return $data;
}
function jsquery(String $sql) {
	return json_encode(query($sql));
}

?>
