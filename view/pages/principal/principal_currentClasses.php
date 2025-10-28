<?php 
require "../../../controller/principal/main.php";
list($schoolyear, $output) = currentclasses();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../../model/picture/Logo_1.png" />
    <title>Enroll Class</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../../assets/style/style.css?????">
    <link rel="stylesheet" href="../../../assets/style/search.css????">
</head>

<body>
    <div class="dashboard">
        <?php $cur = 'current'; include_once "../../../controller/principal/sidebar.php"; ?>
        <div class="main-content">
            <header class="topbar">
                <button class="menu-toggle"><i class="fas fa-bars"></i></button> 
                <div class="topbar-middle">
                    <b>CURRENT ENROLLED CLASSES  <?php echo '(S.Y. ' . $schoolyear . ')'; ?></b>
                </div>
            </header>
            <main class="dashboard-content">
                <div class="card">
                    <form method="POST" class="search-form mb-3">
                        <div class="search-container">
                            <label for="filterGrade" class="form-label">Filter by Grade Level:</label>
                            <select class="form-select" id="filterGrade" name="filterCurrent">
                                <option value="" selected>Choose Grade Level</option>
                                <?php echo gradelvl(); ?>
                            </select>
                        </div>
                        <div class="button-container">
                            <button type="button" id="search-button" class="btn btn-secondary" name="btnCurrent">Filter</button>
                            <button type="button" id="reset-button" class="btn btn-outline-secondary">Reset</button>
                        </div>
                    </form>
                    <h2>ALL ENROLLED CLASSES</h2>
                    <div class="table-location">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>School Year</th>
                                    <th>Grade Level</th>
                                    <th>Section</th>
                                    <th>Adviser</th>
                                    <th>Number of Students</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="ga" id="studentlist-table-body">
                               <?php echo $output; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="../../../model/function.js"></script>
    <script>
        document.getElementById("search-button").addEventListener("click", function() {
            const GL = document.getElementById("filterGrade").value.toLowerCase();
            filterTable("studentlist-table-body", GL, '', 1, null);
        });
        document.getElementById("reset-button").addEventListener("click", function() {
            document.getElementById("filterGrade").value = '';
            filterTable("studentlist-table-body", '', '', 0, null);
        });
    </script>
    <script src="../../../assets/script/script.js"></script>
</body>
</html>
