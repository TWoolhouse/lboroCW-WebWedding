<?php header('Content-type: application/json');
include_once "../api.php";

$sql =
"SELECT venue_id id, grade grade, cost cost
FROM catering
;";

$q = query($sql);
$data = array();

if (count($q) > 0) {
	$venue = array();
	$vid = $q[0]["id"];
	foreach ($q as $key => $row) {
		if ($row["id"] != $vid) {
			$data[$vid] = $venue;
			$vid = $row["id"];
			$venue = array();
		}
		$venue[$row["grade"]] = $row["cost"];
	}
	$data[$vid] = $venue;
}

echo json_encode($data);

?>
