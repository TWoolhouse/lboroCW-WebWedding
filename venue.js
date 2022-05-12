(async () => {

	const scope_eval = (scope, script) => Function(`"use strict"; ${script}`).bind(scope)();
	String.prototype.eval = function (scope) {
		return this.replace(/£{(.+?)}/g, (match, g1, offset, string, group) => {
			return scope_eval(scope, `return ${g1};`);
		});
	};
	const date_string = date => `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}`;

	const HTMLnode = src => {
		const dom = document.createElement("template");
		dom.innerHTML = src;
		return dom.content.firstElementChild;
	};

	const ajax = {
		url: (url, params) => url + "?" + new URLSearchParams(params),
		venues: () => fetch(ajax.url("api/venues.php", {})).then(value => value.json()),
		bookings: (start, end) => fetch(ajax.url("api/bookings.php", {
			start: date_string(start),
			end: date_string(end),
		})).then(value => value.json()),
		catering: () => fetch(ajax.url("api/catering.php", {}), {
			importance: "low",
		}).then(value => value.json()),
	};

	const template = {
		venue: "",
		active: "",
		day: {
			free: "",
			used: "",
			empty: "",
		},
	};
	{
		const templates = document.getElementById("template")
		for (const node of templates.content.children) {
			template[node.id] = node.innerHTML;
		}
		const days = templates.content.querySelector("#day");
		template.day = {};
		for (const node of days.children) {
			template.day[node.id] = node.innerHTML;
		}
		templates.remove();
	}

	const venues = {};
	const view_venues = document.querySelector("body>#venues");
	const view_venue = document.querySelector("body>aside #venue");
	const nav = document.querySelector("nav");
	const input = {
		go: nav.querySelector("#go"),
		start: nav.querySelector("#start"),
		end: nav.querySelector("#end"),
	}

	const select = (() => {
		let current = {
			day: null,
			venue: null,
			guests: 0,
			price: 0,
			catering: null,
		};

		const uid_date = date => `T${date.getFullYear()}T${date.getMonth() + 1}T${date.getDate()}`;

		const create_venue = venue => {
			setTimeout(() => venue.node().scrollIntoView({ behavior: "smooth", block: matchMedia("(max-width: 700px)").matches ? "end" : "center" }), 1);
			if (venue == current.venue) return;
			current.venue = venue;
			view_venue.innerHTML = template.active.eval(venue);
			view_venue.querySelector("#close").addEventListener("click", () => select.venue(null));
			const figure = view_venue.querySelector("#pic img").parentElement;
			figure.addEventListener("mouseenter", () => select.image(figure, true));
			figure.addEventListener("mouseleave", () => select.image(figure, false));
			figure.addEventListener("click", () => select.image(figure));

			const guests = view_venue.querySelector("#guests");
			guests.addEventListener("change", event => {
				if (event.isTrusted) {
					if (guests.valueAsNumber == NaN || guests.valueAsNumber < guests.min || guests.valueAsNumber > guests.max)
						guests.valueAsNumber = current.guests;
					else
						current.guests = guests.valueAsNumber;
				} else {
					current.guests = guests.valueAsNumber;
				};
				guests.classList[current.guests > guests.max ? "add" : "remove"]("overlimit");
				pricing();
			})
			guests.valueAsNumber = current.guests;
			guests.dispatchEvent(new Event("change"));
			select.update.booking();
			select.update.catering();
			pricing();
		};

		const create_calendar = () => {
			const cal = view_venue.querySelector("#calendar #dates");
			let week = document.createElement("tr");
			cal.innerHTML = "";
			cal.appendChild(week);

			if (current.venue.dates.length == 0) return;

			for (let i = 1; i < current.venue.dates[0].date.getDay(); i++) {
				week.innerHTML += template.day.empty;
			}
			for (const day of current.venue.dates) {
				if (day.date.getDay() == 1 && week.innerHTML != "") {
					week = document.createElement("tr");
					cal.appendChild(week);
				}
				const node = HTMLnode((day.booked ? template.day.used : template.day.free).trim().eval({
					day: day.date.getDate(),
					date: `${day.date.getDate()}/${day.date.getMonth() + 1}/${day.date.getFullYear()}`,
					uid: uid_date(day.date),
				}));

				week.appendChild(node);
				if (!day.booked)
					node.addEventListener("click", () => select.day(Object.assign({ node: node }, day)));
			}
		};

		function pricing() {
			if (current.venue === null) current.price = "Select a Venue";
			else if (current.day === null) current.price = "Select a Date";
			else if (current.catering === null) current.price = "Select a Catering Grade";
			else
				current.price =
					"£" + (Number.parseInt(current.day.date.getDay() > 4 ? current.venue.price_wkend : current.venue.price_wkday)
						+ current.guests * Number.parseInt(current.catering.cost));
			view_venue.querySelector("#price #£").innerText = current.price;
		};

		return {
			venue: venue => {
				console.log(venue);
				if (current.venue !== null) current.venue.node().classList.remove("active");
				if (venue === null) {
					current.venue = venue;
					view_venue.parentElement.classList.remove("active");
					return;
				}
				create_venue(venue);
				view_venue.parentElement.classList.add("active");
				if (venue !== null) venue.node().classList.add("active");
			},
			day: day => {
				if (current.day !== null) current.day.node.classList.remove("active");
				day.node.classList.add("active");
				current.day = day;
				pricing();
			},
			update: {
				booking: () => {
					if (current.venue && current.venue.active()) {
						create_calendar();
						if (current.day !== null && current.venue.dates.find(({ date, booked }) => !booked && date.toString() == current.day.date.toString())) {
							select.day(Object.assign({}, current.day, {
								node: view_venue.querySelector(`#calendar #dates #${uid_date(current.day.date)}`)
							}));
						} else {
							const node = view_venue.querySelector("#calendar .free");
							if (node)
								select.day(Object.assign({ node: node }, current.venue.dates.find(({ date }) => uid_date(date) == node.id)));
							else current.day = null;
						}
					}
					else select.venue(null);
				},
				catering: () => {
					if (!current.venue) return;
					const node = view_venue.querySelector("#catering #info");
					node.innerHTML = "";
					for (let grade = 1; grade <= 5; grade++) {
						const good = current.venue.catering.hasOwnProperty(grade);
						const msg = good ? `£${current.venue.catering[grade]} pp` : `Grade ${grade}`;
						const child = HTMLnode(`<span id="G${grade}" class="grade tooltip ${good ? "encircle" : "na"}">
						${grade}
						<span class="tooltiptext">${msg}</span>
						</span>`);
						if (good)
							child.addEventListener("click", () => {
								if (current.catering !== null) current.catering.node.classList.remove("active");
								current.catering = { node: child, grade: grade, cost: current.venue.catering[grade] };
								current.catering.node.classList.add("active");
								pricing();
							});
						node.appendChild(child);
					}
					let first = null;
					if (current.catering !== null) {
						first = node.querySelector(`#G${current.catering.grade}.encircle`);
					} if (first === null)
						first = Array.from(node.children).find(elm => !elm.classList.contains("na"));
					if (first !== null) first.dispatchEvent(new Event("click"));
					pricing();
				},
			},
			image: (figure, flag) => {
				const func = (flag === undefined || flag === null ? "toggle" : (flag ? "remove" : "add"))
				// let figure = document.querySelector("#pic img").parentElement;
				figure.classList[func]("shrunk");
			},
		};
	})();

	class Venue {

		id = 0;
		bookings = 0;
		verified = true;
		name = "Venue Name";
		image = "https://picsum.photos/300";
		dates = [];
		price_wkday = 0;
		price_wkend = 0;
		catering = {};

		price_starting() { return Math.min(this.price_wkday, this.price_wkend); }

		_element = null;

		constructor(obj) {
			Object.assign(this, obj);
		}

		node() {
			if (this._element !== null) return this._element;
			this._element = document.createElement("article");
			this._element.id = this.id;
			this._element.classList.add("venue");
			this._element.innerHTML = template.venue.eval(this);
			this._element.addEventListener("click", () => select.venue(this));

			return this._element;
		}

		update_bookings(start, end, booked_dates) {
			const booked = booked_dates.map(ds => date_string(new Date(ds)));
			const incr = day + 1000 * 60 * 60; // 1 day + 1 hour
			this.dates = [];
			const final = new Date(end.getFullYear(), end.getMonth(), end.getDate());
			let current = new Date(start.getFullYear(), start.getMonth(), start.getDate());
			while (current.getTime() <= final.getTime()) {
				this.dates.push({
					date: current,
					booked: booked.includes(date_string(current)),
				});
				current = new Date(current.getTime() + incr);
				current = new Date(current.getFullYear(), current.getMonth(), current.getDate());
			}
			if (this.dates.length === 0) return this.hide();
			for (const { booked } of this.dates) {
				if (!booked) {
					this.show();
					return;
				}
			} return this.hide();
		}

		update_catering(grades) {
			this.catering = grades;
			const node = this.node().querySelector("#info #catering #info");
			node.innerText = `£${Math.min(...Object.values(this.catering))}`;
		}

		active() { return !this.node().classList.contains("hide"); }
		hide() { this.node().classList.add("hide"); }
		show() { this.node().classList.remove("hide"); }

	};

	const ready = (async () => {
		const vs = await ajax.venues();
		for (const v of vs) {
			const venue = new Venue(v);
			venues[venue.id] = venue;
			view_venues.appendChild(venue.node());
			venue.show();
		}
	})();

	(async () => {
		await ready;
		const cs = await ajax.catering();
		for (const vid in cs) {
			venues[vid].update_catering(cs[vid]);
		}
		select.update.catering();
	})();

	// TODO: MAKE DATE NOT TIME TRAVEL
	const day = 1000 * 60 * 60 * 24;
	input.start.valueAsDate = new Date();
	input.end.valueAsDate = new Date(Date.now() + 13 * day);

	// Main
	input.go.addEventListener("click", async () => {
		const req = ajax.bookings(input.start.valueAsDate, input.end.valueAsDate);
		await ready;
		console.log(venues);
		const bookings = await req;
		for (const vid in venues) {
			venues[vid].hide();
		}
		for (const vid in bookings) {
			venues[vid].update_bookings(input.start.valueAsDate, input.end.valueAsDate, bookings[vid]);
		}
		select.update.booking();
		console.log(bookings);
	});
	input.go.dispatchEvent(new Event("click"));

})();
