document.addEventListener("DOMContentLoaded", function () {
	const menuToggle = document.querySelector(".menu-toggle");
	const sidebar = document.querySelector(".sidebar");
	const searchInput = document.querySelector(".search-input");
	const searchIcon = document.querySelector(".search-icon");

	menuToggle.addEventListener("click", function () {
		sidebar.classList.toggle("active");
	});

	document.addEventListener("click", function (event) {
		const isClickInside =
			sidebar.contains(event.target) || menuToggle.contains(event.target);
		if (!isClickInside && sidebar.classList.contains("active")) {
			sidebar.classList.remove("active");
		}
	});

	const currentDateElement = document.getElementById("current-date");
	const options = {
		weekday: "long",
		year: "numeric",
		month: "long",
		day: "numeric",
	};
	const currentDate = new Date().toLocaleDateString("en-US", options);
	currentDateElement.textContent = currentDate;
});
