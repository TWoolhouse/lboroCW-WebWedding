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

$ipt_min = $_GET["minCapacity"];
$ipt_max = $_GET["maxCapacity"];

if (!(is_numeric($ipt_min) && is_numeric($ipt_max)) || ($ipt_min > $ipt_max)) {
	echo "<tr><th>$ipt_min - $ipt_max is not a valid capacity range</th></tr>";
	echo "<tr><td style=\"text-align: center;\">Redirecting in 5 seconds...</td></tr>";
	echo "<script>setTimeout(() => window.history.go(-1), 5000);</script>";
} else {
	$ipt_min = intval($ipt_min);
	$ipt_max = intval($ipt_max);

	$sql = "SELECT name, capacity, weekday_price, weekend_price FROM venue WHERE licensed AND capacity >= $ipt_min AND capacity <= $ipt_max ORDER BY capacity asc;";
	$headers = ["Venue", "Capacity", "Price: Weekday", "Price: Weekend"];

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
