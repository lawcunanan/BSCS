// Event data storage

//CALENDAR
function initializeCalendar(events) {
	$("#calendar").fullCalendar("destroy");
	$("#calendar").fullCalendar({
		header: {
			left: "prev,today",
			center: "title",
			right: "next",
		},

		events: formatEventsForCalendar(events),
		eventClick: function (events, jsEvent) {
			showPopover(events, jsEvent.target);
		},
	});
}

function formatEventsForCalendar(events) {
	return events.map((event) => {
		const formattedEvent = {
			id: event.id,
			title: event.title,
			start: moment(event.date.split(" - ")[0]).format("YYYY-MM-DD"),
			color: event.type.toLowerCase() === "school-wide" ? "#dc3545" : "#198754",
			allDay: true,
			description: event.description,
			type: event.type,
			date: event.date,
			time: event.time,
			name: event.requestedby,
		};

		if (event.date.includes(" - ")) {
			const endDate = event.date.split(" - ")[1].trim();
			formattedEvent.end = moment(endDate).add(1, "days").format("YYYY-MM-DD");
		}
		return formattedEvent;
	});
}

function showPopover(event, element) {
	$("[data-toggle='popover']").popover("dispose");

	const popoverContent = `
		<div>
		    <span class = "label">Title:</span>
			<p> ${event.title}</p>
			<span class = "label">Description:</span>
			<p>${event.description}</p>
			<span class = "label">Date:</span>
			<p>${event.date}</p>
			<span class = "label">Time:</span> 
			<p>${event.time}</p>
			<span class = "label">Requested by:</span> 
			<p>${event.name}</p>
		</div>
	`;

	$(element).popover({
		title: event.type,
		content: popoverContent,
		html: true,
		placement: "top",
		trigger: "manual",
		template: `
            <div class="popover ${event.type}" role="tooltip">
                <div class="arrow"></div>
                <h3 class="popover-header"></h3>
                <div class="popover-body"></div>
            </div>
        `,
	});

	$(element).popover("show");

	// Hide the popover when clicking outside
	$(document).on("click", function (e) {
		if (
			!$(element).is(e.target) &&
			$(element).has(e.target).length === 0 &&
			$(".popover").has(e.target).length === 0
		) {
			$(element).popover("hide");
		}
	});
}

function search() {
	const searchInput = document.getElementById("searchInput");
	searchInput.addEventListener("input", function () {
		const query = searchInput.value.toLowerCase();

		if (query === "") {
			initializeCalendar(events[0]);
		} else {
			const filteredEvents = events[0].filter((event) =>
				event.title.toLowerCase().includes(query)
			);
			initializeCalendar(filteredEvents);
		}
	});
}

function setupEventHandlers() {
	$("#singleDayEvent").on("change", function () {
		$("#endDateContainer").toggle(!this.checked);
	});

	$("#includeTime").on("change", function () {
		$(".time-inputs").toggle(this.checked);
	});
}
