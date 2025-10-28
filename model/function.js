function getCurrentDate(classname) {
	const dateOptions = {
		weekday: "long",
		day: "numeric",
		month: "short",
		year: "numeric",
		hour: "numeric",
		minute: "2-digit",
		hour12: true,
	};

	const currentDate = new Date().toLocaleString("en-PH", dateOptions);
	const elements = document.getElementsByClassName(classname);
	for (let i = 0; i < elements.length; i++) {
		elements[i].textContent = currentDate;
	}
}

function preview_uploadexcel(studentData) {
	document.getElementById("schoolYear").textContent =
		studentData.Handled["school year"];
	document.getElementById("gradeLevel").textContent =
		studentData.Handled["grade level"];
	document.getElementById("section").textContent =
		studentData.Handled["section"];

	const tableContent = document.getElementById("tableContent");
	const studentList = studentData.Data;

	for (const student in studentList) {
		const studentInfo = studentList[student];
		const row = `<tr>
        <td>${studentInfo.lrn}</td>
        <td>${studentInfo.name}</td>
        <td>${studentInfo.sex}</td>
        <td>${studentInfo.birth}</td>
        <td>${studentInfo.age}</td>
		<td>${studentInfo.ip}</td>
		<td>${studentInfo.religion}</td>
		<td>${studentInfo.tongue}</td>
        <td>${studentInfo["father name"]}</td>
		<td>${studentInfo["mother name"]}</td>
        <td>${studentInfo["guardian name"]}</td>
		<td>${studentInfo.contact}</td>
        <td>${studentInfo.purok}</td>
        <td>${studentInfo.barangay}</td>
        <td>${studentInfo.municipal}</td>
        <td>${studentInfo.province}</td>
        </tr>`;
		tableContent.innerHTML += row;
	}
	var myModal = new bootstrap.Modal(document.getElementById("uploadExcel"));
	myModal.show();
}

function preview_pendingexcel(studentData) {
	document.getElementById("schoolYear1").textContent =
		studentData.Handled["school year"];
	document.getElementById("gradeLevel1").textContent =
		studentData.Handled["grade level"];
	document.getElementById("section1").textContent =
		studentData.Handled["section"];

	const tableContent = document.getElementById("tableContent1");
	const students = studentData.Student;

	// Clear existing rows
	tableContent.innerHTML = "";

	for (const lrn in students) {
		const studentInfo = students[lrn];
		const row = `<tr>
						<td>${lrn}</td>
						<td>${studentInfo[0]}</td>
						<td>${studentInfo[1]}</td>
						<td>${studentInfo[2]}</td>
						<td>${studentInfo[3]}</td>
						<td>Pending</td>
					</tr>`;

		tableContent.innerHTML += row;
	}

	var myModal = new bootstrap.Modal(document.getElementById("pendingExcel"));
	myModal.show();
}

function previewGrades(studentData) {
	document.getElementById("schoolYear2").textContent =
		studentData.Handled["school year"];
	document.getElementById("gradeLevel2").textContent =
		studentData.Handled["grade level"];
	document.getElementById("section2").textContent =
		studentData.Handled["section"];

	const gradesPreviewContent = document.getElementById("gradesPreviewContent");
	gradesPreviewContent.innerHTML = "";
	let output = ``; // Use let instead of $output for variable naming consistency
	for (const key in studentData.Data) {
		const student = studentData.Data[key];
		output += `<h6>${student.Name}</h6>`;
		output += `
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>1st Quarter</th>
                        <th>2nd Quarter</th>
                        <th>3rd Quarter</th>
                        <th>4th Quarter</th>
                    </tr>
                </thead>
                <tbody>`;
		for (const subject in student) {
			if (subject !== "Name") {
				output += `
                    <tr>
                        <td>${subject}</td>
                        <td>${student[subject]["1"] || "N/A"}</td>
                        <td>${student[subject]["2"] || "N/A"}</td>
                        <td>${student[subject]["3"] || "N/A"}</td>
                        <td>${student[subject]["4"] || "N/A"}</td>
                    </tr>`;
			}
		}
		output += `
                </tbody>
            </table>
            <br/>`;
	}
	gradesPreviewContent.innerHTML += output;

	var myModal = new bootstrap.Modal(document.getElementById("gradesExcel"));
	myModal.show();
}

function showalert(color, messagee) {
	let message = `
	       <div  class='alert alert-${color} alert-dismissible fade show' role='alert'>
				${messagee}
				<button
					type='button'
					class='btn-close'
					data-bs-dismiss='alert'
					aria-label='Close'
				></button>
			</div>
	`;

	document.getElementById("alertt").innerHTML = message;
}

function generatePDF(val) {
	const date = new Date();
	const options = {
		weekday: "long",
		year: "numeric",
		month: "long",
		day: "numeric",
	};
	const formattedDate = date
		.toLocaleDateString("en-US", options)
		.replace(",", "");
	const filename = `document_${formattedDate}.pdf`;

	const opt = {
		margin: 0,
		filename: filename,
		image: { type: "png", quality: 1 },
		html2canvas: { scale: 2 },
		jsPDF: { unit: "in", format: [8.5, 11], orientation: "portrait" },
	};
	const element = val;
	html2pdf().from(element).set(opt).save();
}

let selectedRow = null;
function selectRow(radio) {
	if (selectedRow) {
		selectedRow.classList.remove("table-active");
	}

	const row = radio.closest("tr");
	selectedRow = row;
	selectedRow.classList.add("table-active");
}

function formatText(command) {
	document.execCommand(command, false, null);
}

function insertText(text) {
	const editor = document.getElementById("editor");
	const selection = window.getSelection();
	const range = selection.getRangeAt(0);
	range.deleteContents();
	range.insertNode(document.createTextNode(text));
	selection.removeAllRanges();
	selection.addRange(range);
}

function changeFontSize() {
	const selectedSize = document.getElementById("fontSize").value;
	const selection = window.getSelection();

	if (selection.rangeCount > 0) {
		const range = selection.getRangeAt(0);
		const selectedText = range.toString();

		if (selectedText.length > 0) {
			const span = document.createElement("span");
			span.style.fontSize = selectedSize;
			span.textContent = selectedText;

			range.deleteContents();
			range.insertNode(span);
		}
	}
}

function toggleCase(caseType) {
	const editor = document.getElementById("editor");
	const selection = window.getSelection();
	const range = selection.getRangeAt(0);
	const selectedText = range.toString();

	if (selectedText.length > 0) {
		const newText =
			caseType === "uppercase"
				? selectedText.toUpperCase()
				: selectedText.toLowerCase();

		range.deleteContents();
		range.insertNode(document.createTextNode(newText));
	}
}
function setID(id, name) {
	document.getElementById(name).value = id;
}

function toggleAvailableSection() {
	document
		.getElementById("reasonSelect")
		.addEventListener("change", function () {
			const availableSectionDiv = document.getElementById(
				"availableSectionDiv"
			);
			if (this.value === "Section") {
				availableSectionDiv.style.display = "block"; // Show the section div
			} else {
				availableSectionDiv.style.display = "none"; // Hide the section div
			}
		});
}

function populateTeacherModal(data) {
	console.log(data);
	const cardContainer = document.getElementById("card-container");
	cardContainer.innerHTML = "";

	for (const key in data) {
		if (data.hasOwnProperty(key)) {
			const teacher = data[key];
			const card = document.createElement("div");
			card.className = "card";

			card.innerHTML = `
							<img class="card-img-top" src="../../../model/picture/User_${teacher.id}.png" alt="${teacher.name}" />
							<div class="card-body">
								<h4 class="card-title">${teacher.name}</h4>
								<p class="card-text">${teacher.email}</p>
								<a href="#" class="btn btn-primary" onclick = "setID(${teacher.id}, 'btnSelect as Adviser')" id="${teacher.id}">Select</a>
							</div>
						`;

			cardContainer.appendChild(card);
		}
	}

	const searchInput = document.getElementById("searchinput");
	searchInput.addEventListener("input", () => {
		const searchValue = searchInput.value.toLowerCase();
		const cards = cardContainer.getElementsByClassName("card");

		Array.from(cards).forEach((card) => {
			const cardTitle = card
				.getElementsByClassName("card-title")[0]
				.textContent.toLowerCase();
			if (cardTitle.includes(searchValue)) {
				card.style.display = "flex";
			} else {
				card.style.display = "none";
			}
		});
	});
}

document.addEventListener("DOMContentLoaded", function () {
	const selectAdviserBtn = document.getElementById("btnSelect as Adviser");
	const cardContainer = document.querySelector(".card-container");
	let selectedCard = null;

	selectAdviserBtn.disabled = true;

	cardContainer.addEventListener("click", function (event) {
		const clickedBtn = event.target.closest(".btn-primary, .btn-danger");
		if (!clickedBtn) return;

		const card = clickedBtn.closest(".card");

		if (selectedCard === card) {
			unselectCard(card);
			selectedCard = null;
			selectAdviserBtn.disabled = true;
		} else {
			if (selectedCard) {
				unselectCard(selectedCard);
			}
			selectCard(card);
			selectedCard = card;
			selectAdviserBtn.disabled = false;
		}

		updateCardsAppearance();
	});

	function selectCard(card) {
		card.classList.add("selected");
		card.style.opacity = "1";
		card.querySelector(".btn-primary, .btn-danger").textContent = "Unselect";
		card.querySelector(".btn-primary, .btn-danger").classList.add("btn-danger");
		card
			.querySelector(".btn-primary, .btn-danger")
			.classList.remove("btn-primary");
	}

	function unselectCard(card) {
		card.classList.remove("selected");
		card.style.opacity = "1";
		card.querySelector(".btn-primary, .btn-danger").textContent = "Select";
		card
			.querySelector(".btn-primary, .btn-danger")
			.classList.add("btn-primary");
		card
			.querySelector(".btn-primary, .btn-danger")
			.classList.remove("btn-danger");
	}

	function updateCardsAppearance() {
		const allCards = cardContainer.querySelectorAll(".card");
		allCards.forEach((card) => {
			if (!card.classList.contains("selected")) {
				card.style.opacity = selectedCard ? "0.6" : "1";
			}
		});
	}
});

function updateButtonName(elementId, newName) {
	var element = document.getElementById(elementId);
	if (element) {
		element.setAttribute("name", newName);
	}
}

function NewTemplate() {
	const newTemplateInput = document.getElementById("newTemplate");
	const templateSelect = document.getElementById("docuTypee");
	const btnUpdate = document.getElementById("btnUpdate");

	if (templateSelect.value === "newTemplate") {
		newTemplateInput.style.display = "block";
		newTemplateInput.setAttribute("required", "required");
		btnUpdate.textContent = "Insert";
		document.getElementById("editor").innerHTML = "";
		document.getElementById("certificateContent").innerHTML = "";
	} else {
		newTemplateInput.style.display = "none";
		newTemplateInput.removeAttribute("required");
		btnUpdate.textContent = "Update";
	}
}

function searchTable(tableBodyId, searchInputId) {
	const searchValue = document
		.getElementById(searchInputId)
		.value.toLowerCase();
	const rows = document.querySelectorAll(`#${tableBodyId} tr`);

	rows.forEach((row) => {
		const cells = row.querySelectorAll("td");
		let found = false;

		cells.forEach((cell) => {
			if (cell.textContent.toLowerCase().includes(searchValue)) {
				found = true;
			}
		});

		row.style.display = found ? "" : "none";
	});
}

function filterTable(tableBodyId, fValue, sValue, fIndex, sIndex) {
	const rows = document.querySelectorAll(`#${tableBodyId} tr`);

	rows.forEach((row) => {
		const cells = row.querySelectorAll("td");
		const firstValueMatches = fValue
			? cells[fIndex].textContent.toLowerCase() === fValue.toLowerCase()
			: true;
		const secondValueMatches = sValue
			? cells[sIndex].textContent.toLowerCase() === sValue.toLowerCase()
			: true;

		if (fValue && sValue) {
			row.style.display = firstValueMatches && secondValueMatches ? "" : "none";
		} else if (fValue) {
			row.style.display = firstValueMatches ? "" : "none";
		} else if (sValue) {
			row.style.display = secondValueMatches ? "" : "none";
		} else {
			row.style.display = "";
		}
	});
}

// ADDRESS API
function setCode(id_dropdown) {
	return document.getElementById(id_dropdown).value.replace(/\D/g, "");
}

function fetchDataAndPopulateDropdown(
	url,
	dropdownId,
	valueField,
	textField,
	filterField,
	filterValue
) {
	fetch(url)
		.then((response) => response.json())
		.then((data) => {
			const dropdown = document.getElementById(dropdownId);
			while (dropdown.childNodes.length > 2) {
				dropdown.removeChild(dropdown.lastChild);
			}
			//FILTER
			const filteredData = filterField
				? data.filter((item) => item[filterField] === filterValue)
				: data;
			//SORT
			filteredData.sort((a, b) => {
				if (a[textField] < b[textField]) return -1;
				if (a[textField] > b[textField]) return 1;
				return 0;
			});

			filteredData.forEach((item) => {
				const option = document.createElement("option");
				option.value = item[valueField] + "" + item[textField];
				option.textContent = item[textField];
				dropdown.appendChild(option);
			});
		})
		.catch((error) => {
			console.error("Error fetching JSON:", error);
			alert("Error fetching data. Please try again later.");
		});
}

function setProvince(province_dropdown) {
	fetchDataAndPopulateDropdown(
		"https://raw.githubusercontent.com/isaacdarcilla/philippine-addresses/main/province.json",
		province_dropdown,
		"province_code",
		"province_name"
	);
}

function setCity(code, city_dropdown) {
	fetchDataAndPopulateDropdown(
		"https://raw.githubusercontent.com/isaacdarcilla/philippine-addresses/main/city.json",
		city_dropdown,
		"city_code",
		"city_name",
		"province_code",
		code
	);
}

function setBarangays(code, barangay_dropdown) {
	fetchDataAndPopulateDropdown(
		"https://raw.githubusercontent.com/isaacdarcilla/philippine-addresses/main/barangay.json",
		barangay_dropdown,
		"brgy_code",
		"brgy_name",
		"city_code",
		code
	);
}

function setimg(id, image) {
	var input = document.getElementById(image);
	var profileImage = document.getElementById(id);

	var reader = new FileReader();
	reader.onload = function (e) {
		profileImage.src = e.target.result;
	};

	reader.readAsDataURL(input.files[0]);
}
