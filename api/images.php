<?php
header('Content-type: application/json');
header("Cache-Control: public");
include_once "../api.php";

$sql =
"SELECT count(*) as count
FROM venue
;";
$venues = query($sql)[0]["count"];

const URL = "https://api.unsplash.com/search/photos";
const ACS_KEY = "valN2mMIkXxS6YvxNJdrZ3vRC9wayssCwlqPt2jmze0";
$search = "wedding venues";

$query = http_build_query([
	"query" => $search,
	"per_page" => $venues,
	"client_id" => ACS_KEY,
]);

$uri = URL . "?" . $query;
echo file_get_contents($uri);

?>
