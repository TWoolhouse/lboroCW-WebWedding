<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="simple.css">
	<title>Catering</title>

	<style>
		td {
			width: 3em;
		}
	</style>

</head>
<body>
<table>
<tr><th id=title>➡️ Cost per Person<hr>⬇️ Party Size</th>
<?php
// echo "<tr><th>THE TITLE</th>";
for ($i = 1; $i <= 5; ++$i) {
	echo "<th>";
	echo $_GET["c$i"];
	echo "</th>";
}
echo "</tr>";
for ($pp = $_GET["min"]; $pp <= $_GET["max"]; $pp += 5) {
	echo "<tr>";
	echo "<td class=\"index\">$pp</td>";
	for ($i = 1; $i <= 5; ++$i) {
	echo "<td>";
	echo $_GET["c$i"] * $pp;
	echo "</td>";
}
	echo "</tr>";
}
?>
</table>
</body>
</html>
