<?php header('Content-type: application/json');
include_once "api.php";

$sql =
"SELECT venue.venue_id id, name name, licensed verified, capacity capacity, weekday_price price_wkday, weekend_price price_wkend, count(booking_date) \"bookings\"
FROM venue JOIN venue_booking ON venue.venue_id = venue_booking.venue_id
GROUP BY venue.venue_id
;";
echo jsquery($sql);

?>
