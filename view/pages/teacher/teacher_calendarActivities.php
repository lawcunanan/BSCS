<?php 
require "../../../controller/teacher/main.php";
list($count, $output) = calendarpending();   
$calendar = json_encode(calendarapprove());
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../../model/picture/Logo_1.png" />
    <title>Calendar of Activities</title>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../../assets/style/style.css?????" />
    <link rel="stylesheet" href="../../../assets/style/search.css???" />
    <link rel="stylesheet" href="../../../assets/style/calendar.css??">
    <link rel="stylesheet" href="../../../assets/style/pendingEvents.css??">
    <link rel="stylesheet" href="../../../assets/style/modalstyle.css???????" />
    <style>
        .button-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <?php $cur = 'calendar'; include_once "../../../controller/teacher/sidebar.php"; ?>

        <div class="main-content">
            <header class="topbar">
                <button class="menu-toggle"><i class="fas fa-bars"></i></button>
                <div class="topbar-middle">
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
                        <i class="fas fa-calendar-alt"></i> <span class="date"></span>
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

                <div style="display: flex; flex-direction: column; gap: 30px">
                    <div class="events-card">
                        <div class="upcoming-events-header">
                            <h2 class="section-title">Upcoming Activities</h2>
                        </div>
                        <div class="upcoming-events-container" id="upcomingEvents">
                            <?php echo calendarupcoming(); ?>
                        </div>	
                    </div>

                    <div class="events-card">
                        <div class="upcoming-events-header">
                            <h2 class="section-title">Rejected Activities</h2>
                        </div>
                        <div class="upcoming-events-container" id="upcomingEvents">
                            <?php echo calendarrejected(); ?>
                        </div>	
                    </div>
                </div>
                
            </div>

            <div class="main-content">
                <div class="card">
                    <div class="header-with-counter">
                        <h2>ACTIVITIES WITH PENDING APPROVAL</h2>
                        <span class="pending-counter" id="pendingCounter"><?php echo $count; ?></span>
                    </div>

                    <div class="pending-events" id="pending-events">
                        <?php echo $output; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php 
            echo addevent();
            echo calendardelete();
        ?>
         
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
        <script src="../../../assets/script/calendar.js??"></script>
        <script src="../../../model/function.js??"></script>
        <script>
            updateButtonName("btnSave Changes", "btnSaveChanges");
            updateButtonName("btnSave Event", "btnSaveEvent");
            let events = [<?php echo $calendar; ?>];

            $(document).ready(function () {
                initializeCalendar(events[0]);
                setupEventHandlers(events[0]);
            });

            search();
            getCurrentDate("date");
        </script>
    </div>
</body>
</html>
