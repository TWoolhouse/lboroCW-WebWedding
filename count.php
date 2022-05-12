<?php include_once "db.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="simple.css">
	<title>Capacity</title>

</head>
<body>
<table>
<?php

$ipt_month = $_GET["month"];
if ($ipt_month < 1 || $ipt_month > 12) {
	echo "<tr><th>$ipt_month is not a valid month</th></tr>";
	echo "<tr><td style=\"text-align: center;\">Redirecting in 5 seconds...</td></tr>";
	echo "<script>setTimeout(() => window.history.go(-1), 5000);</script>";
} else {
	$sql = "SELECT v.name, count(v.venue_id) FROM venue v JOIN venue_booking ON v.venue_id = venue_booking.venue_id WHERE booking_date LIKE \"%-%$ipt_month-%\" GROUP BY v.venue_id ORDER BY count(v.venue_id) desc;";
	$headers = ["Venue", "Number of Bookings"];

	$result = $conn->query($sql);

	// Headers
	echo "<tr>";
	foreach ($headers as $header) {
		echo "<th>$header</th>";
	}
	echo "</tr>";

	// Data Values
	$len = count($headers);
	while ($row=$result->fetch()){
		echo "<tr><td class=\"index\">" . $row[0] . "</td>";
		for ($i = 1; $i < $len; $i++) {
			echo "<td>" . $row[$i] . "</td>";
		}
	}
}
?>
</table>
</body>
</html>
