<?php require "../../../controller/principal/main.php";
list($count, $output) = calendarpending();  
$calendar = json_encode(calendarapprove());

// sendEventEmail('mikhaelaespiritu@gmail.com', 
//                'Upcoming', 
// 			   'Foundation Day', 
// 			   'This commemorates the founding of the school with activities like parades, talent shows, and exhibitions, celebrating school history and community spirit.', 
// 			   'Nov 15, 2024', 
// 			   '08:00am - 03:00pm',
// 			    'Division-Wide');
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		 <link rel="shortcut icon" href="../../../model/picture/Logo_1.png" />
		<title>Calendar of Activities</title>

		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="../../../assets/style/style.css??" />
		<link rel="stylesheet" href="../../../assets/style/search.css???" />
		<link rel="stylesheet" href="../../../assets/style/calendar.css???" />
		<link rel="stylesheet" href="../../../assets/style/pendingEvents.css?????" />
		<link rel="stylesheet" href="../../../assets/style/modalstyle.css???" />
	
		<style>
			.active{
				background:none;
			}
		</style>
	</head>

	<body>
		<div class="dashboard">
			<?php $cur = 'calendar' ; include_once  "../../../controller/principal/sidebar.php"; ?>
			<div class="main-content">
				<header class="topbar">
					<button class="menu-toggle"><i class="fas fa-bars"></i></button>
					<div >
						<b>CALENDAR OF ACTIVITIES</b>
						<button
							class="btn btn-primary"
							style="margin-left: 20px"
							data-bs-toggle="modal"
							data-bs-target="#addEventModal"
						>
							<i class="fas fa-plus"></i> Add Activity
						</button>
					</div>
					<div class="calendar-header">
						<div class="topbar-date">
							<i class="fas fa-calendar-alt"></i> <span class = "date"></span>
						</div>
						<div class="search-container">
							<input
								type="text"
								class="search-input"
								placeholder="Search events..."
								id="searchInput"
							/>
							<i class="fas fa-search search-icon"></i>
						</div>
					</div>
				</header>
				<div class="container">
					<div class="calendar-card">
						<div id="calendar"></div>
					</div>
                   <div style="display: flex; flex-direction:column; gap:30px">
						<div class="events-card">
							<div class="upcoming-events-header">
								<h2 class="section-title">Upcoming Activities</h2>
							</div>
							<div class="upcoming-events-container" id="upcomingEvents">
								<?php  echo calendarupcoming(); ?>
							</div>	
						</div>

						<div class="events-card">
							<div class="upcoming-events-header">
								<h2 class="section-title">Rejected Activities</h2>
							</div>
							<div class="upcoming-events-container" id="upcomingEvents">
								<?php  echo  calendarrejected(); ?>
							</div>	
						</div>
					</div>
				</div>
				<div class="main-content">
					<div class="card">
						<div class="header-with-counter">
							<h2>ACTIVITIES WITH PENDING APPROVAL</h2>
							<span class="pending-counter" id="pendingCounter"><?php echo $count;?></span>
						</div>

						<div class="pending-events" id="pending-events">
							<?php echo $output;?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php 
		    echo addevent();
		    echo calendardelete();
			echo calendarapprov();
			echo calendarreject();
		?>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
		<script src="../../../assets/script/calendar.js???"></script>
		 <script src = "../../../model/function.js??"></script>
		<script>
            updateButtonName("btnSave Changes", "btnSaveChanges");
			updateButtonName("btnSave Event", "btnSaveEvent");
			let events = [<?php echo $calendar; ?>];
            console.log(events)
			$(document).ready(function () {
				initializeCalendar(events[0]);
				setupEventHandlers(events[0]);
				
			});

			search();
			getCurrentDate("date");
		</script>
		<script>
			function swth() {
	           const activeTab = document.querySelector(".tab-pane.active");

				if (activeTab.id === "events") {
					toggleRequired('eve', 'SY')
					toggleRequired('eve', 'gs')
					
				} else if (activeTab.id === "grades") {
					toggleRequired('gs', 'eve')
					toggleRequired('gs', 'SY')
				} else if (activeTab.id === "schoolyear") {
					toggleRequired('SY', 'eve')
					toggleRequired('SY', 'gs')
				}
			}
			document
				.getElementById("yearStart")
				.addEventListener("input", function () {
					const startYear = parseInt(this.value);
					const yearEndInput = document.getElementById("yearEnd");

					if (startYear && startYear >= 2000 && startYear <= 2099) {
						yearEndInput.value = startYear + 1;
					} else {
						yearEndInput.value = "";
					}
				});

			function toggleRequired(setRequiredClass, removeRequiredClass) {
				const setFields = document.querySelectorAll(`.${setRequiredClass}`);
				setFields.forEach(field => {
					field.setAttribute("required", "required");
				});

				const removeFields = document.querySelectorAll(`.${removeRequiredClass}`);
				removeFields.forEach(field => {
					field.removeAttribute("required");
				});
	     	}
		</script>
	</body>
</html>
