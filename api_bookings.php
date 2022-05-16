<?php header('Content-type: application/json');
include_once "api.php";

$start = $_GET["start"];
$end = $_GET["end"];

$sql =
"SELECT venue.venue_id id, booking_date booking
FROM venue JOIN venue_booking ON venue.venue_id = venue_booking.venue_id
WHERE
	booking_date BETWEEN '$start' AND '$end'
ORDER BY venue.venue_id asc, booking_date asc
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
		$venue[] = $row["booking"];
	}
	$data[$vid] = $venue;
}
	echo json_encode($data);
?>
