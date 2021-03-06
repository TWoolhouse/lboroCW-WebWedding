<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Cache-control" content="public">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined">
	<link rel="stylesheet" href="wedding.css">
	<script defer src="venue.js"></script>
	<title>Weddings</title>
</head>

<body>

	<header>
		<h1>Wedding Planner</h1>
		<nav>
			<div class="ipt">
				<label for="start">Start Date</label>
				<input type="date" name="start" id="start">
			</div>
			<div class="ipt">
				<label for="end">End Date</label>
				<input type="date" name="end" id="end">
			</div>
			<div class="ipt">
				<label for="catering">Catering Grade</label>
				<select name="catering" id="catering">
					<option value="0">Any</option>
					<option value="1">Grade 1</option>
					<option value="2">Grade 2</option>
					<option value="3">Grade 3</option>
					<option value="4">Grade 4</option>
					<option value="5">Grade 5</option>
				</select>
			</div>
			<div class="ipt">
				<label for="capacity">Attendees</label>
				<input type="range" value="0" min="0" max="1000" name="capacity" id="capacity">
				<span id="capacity_value">0</span>
			</div>
			<button id="go">Go</button>
		</nav>
	</header>

	<main id="venues"></main>

	<div id="empty">
	</div>

	<aside>
		<article id="venue" class="venue"></article>
	</aside>

	<template id="template">
		<template id="venue">
			<header>
				£{this.name}
			</header>
			<section id="pic">
				<figure style="£{this.verified == '0' ? '' : 'display: none;'}" id="verified" class="tooltip">
					<span id="icon" class="material-icons-outlined">verified_user</span>
					<figcaption class="tooltiptext">Licensed</figcaption>
				</figure>
				<figure id="img">
					<img src="default.png" alt="Venue Photo">
					<figcaption id="credit">
						<a href="£{this.image}">Photo</a> by <a href="https://picsum.photos/">Lorem Picsum</a>
					</figcaption>
				</figure>
			</section>

			<section id="info">
				<section id="price" class="tooltip">
					<span id="£">££{this.price_starting()}</span>
					<span id="info">Starting Price</span>
					<span class="tooltiptext">Venue Starting Price</span>
				</section>
				<section id="capacity" class="tooltip">
					<span id="icon" class="material-icons-outlined">groups</span>
					<span>£{this.capacity}</span>
					<span class="tooltiptext">Maximum Capacity</span>
				</section>
				<section id="rating" class="tooltip">
					<span id="icon" class="material-icons-outlined">thumb_up</span>
					<span>£{this.bookings}</span>
					<span class="tooltiptext">Bookings</span>
				</section>
				<section id="catering" class="tooltip">
					<span id="icon" class="material-icons-outlined">restaurant</span>
					<span id="info">£0</span>
					<span>PP Starting Price</span>
					<span class="tooltiptext">Starting Catering Price Per Person</span>
				</section>
			</section>

		</template>

		<template id="active">
			<header>
				<span>
					£{this.name}
				</span>
			</header>
			<section id="pic">
				<figure style="£{this.verified == '0' ? '' : 'display: none;'}" id="verified" class="tooltip">
					<span id="icon" class="material-icons-outlined">verified_user</span>
					<figcaption class="tooltiptext">Licensed</figcaption>
				</figure>
				<figure id="close" class="tooltip">
					<span id="icon" class="material-icons-outlined">close</span>
					<figcaption class="tooltiptext">Close</figcaption>
				</figure>
				<figure id="img" class="shrunk">
					<img src="default.png" alt="Venue Photo">
					<figcaption id="credit">
						<a href="£{this.image}">Photo</a> by <a href="https://picsum.photos/">Lorem Picsum</a>
					</figcaption>
					<figcaption id="enlarge">Enlarge Image</figcaption>
				</figure>
			</section>
			<section id="price">
				<span id="£">100</span>
			</section>
			<section id="info">
				<section id="capacity" class="tooltip">
					<span id="icon" class="material-icons-outlined">groups</span>
					<input type="number" placeholder="0" min="0" max="£{this.capacity}" name="guests" id="guests">
					<span>/ £{this.capacity}</span>
					<span class="tooltiptext">Attendees</span>
				</section>
				<section id="rating" class="tooltip">
					<span id="icon" class="material-icons-outlined">thumb_up</span>
					<span>£{this.bookings}</span>
					<span class="tooltiptext">Bookings</span>
				</section>
			</section>
			<section id="catering">
				<span id="icon" class="tooltip">
					<span class="material-icons-outlined">restaurant</span>
					<span class="tooltiptext">Catering Grade</span>
				</span>
				<span id="info"></span>
			</section>
			<table id="calendar">
				<thead>
					<tr>
						<th>M</th>
						<th>T</th>
						<th>W</th>
						<th>T</th>
						<th>F</th>
						<th>S</th>
						<th>S</th>
					</tr>
				</thead>
				<tbody id="dates">
				</tbody>
			</table>
			<section id="buy">
				<button>Book!</button>
			</section>
		</template>

		<template id="credit">
			<a id="photo" href="£{this.photo}?utm_source=Wedding%20Venues&utm_medium=referral">Photo</a> by
			<a id="author" href="£{this.userlink}?utm_source=Wedding%20Venues&utm_medium=referral">£{this.username}</a>
			on
			<a id="unslpash" href="https://unsplash.com/?utm_source=Wedding%20Venues&utm_medium=referral">Unsplash</a>
		</template>

		<div id="day">
			<template id="free">
				<td id="£{this.uid}" class="encircle tooltip free">£{this.day}<span class="tooltiptext">££{this.price}<br>£{this.date}</span></td>
			</template>
			<template id="used">
				<td id="£{this.uid}" class="used">£{this.day}</td>
			</template>
			<template id="empty">
				<td></td>
			</template>
		</div>
	</template>

</body>

</html>
