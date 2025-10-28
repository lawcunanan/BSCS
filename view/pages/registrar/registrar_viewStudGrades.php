<?php 
require "../../../controller/registrar/main.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
     <link rel="shortcut icon" href="../../../model/picture/Logo_1.png" />
    <title>Student's Class Information</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../../assets/style/style.css" />
    <link rel="stylesheet" href="../../../assets/style/search.css" />
</head>

<body>
    <div class="dashboard">
        <?php include_once "../../../controller/registrar/sidebar.php"; ?>
        <div class="main-content">
            <header class="topbar">
                <button class="menu-toggle"><i class="fas fa-bars"></i></button>
                <div class="topbar-middle">
                    <b>STUDENT'S CLASS INFORMATION (S.Y. <?php echo $school_year ?>)</b>
                </div>
            </header>
            <main class="dashboard-content">
                <div class="card">
                    <h2>GRADE <?php echo $grade ?> - <?php echo $section ?></h2>
                    <div class="table-responsive">
                        <table class="table table-bordered tab">
                            <thead class="table-secondary">
                                <tr>
                                    <th>FIELD</th>
                                    <th>INFORMATION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php echo studentinfor(); ?>
                            </tbody>
                        </table>
                    </div>
                    <hr />
                    <h2>GRADES LIST</h2>
                    <form method="POST" class="mb-3">
                        <div class="filter-container">
                            <label for="filterQuarter" class="form-label">Filter by Quarter:</label>
                            <select class="form-select" id="filterQuarter" name="filterQuarter_tvsg" onchange="this.form.submit()">
                                <option disabled hidden selected>Select Quarter</option>
                                <option value="">All Quarters</option>
                                <option value="1">1st Quarter</option>
                                <option value="2">2nd Quarter</option>
                                <option value="3">3rd Quarter</option>
                                <option value="4">4th Quarter</option>
                            </select>
                        </div>
                    </form>
                    <h2>Per Quarter Preview</h2>
                    <div class="table-location">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th><?php $num = isset($_POST['filterQuarter_tvsg']) ? $_POST['filterQuarter_tvsg'] : 1; echo quarter($num); ?> Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php echo perquarterpreview(); ?>
                            </tbody>
                        </table>
                    </div>
                    <h2>All Quarters Preview</h2>
                    <div class="table-location">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>1st Quarter</th>
                                    <th>2nd Quarter</th>
                                    <th>3rd Quarter</th>
                                    <th>4th Quarter</th>
                                    <th>Subject Average</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php echo quarterpreview(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="../../../assets/script/script.js"></script>
</body>
</html>

