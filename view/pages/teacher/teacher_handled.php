<?php 
require "../../../controller/teacher/main.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="shortcut icon" href="../../../model/picture/Logo_1.png" />
    <title>Handled Classes</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../../assets/style/style.css???????">
    <link rel="stylesheet" href="../../../assets/style/search.css">
    <style>
        .filter {
            display: flex;
            align-items: flex-end;
            gap: 15px;
            flex-wrap: wrap;
        }
        .filter-container {
            flex: 1;
            min-width: 200px;
            position: relative;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <?php $cur = 'handled'; include_once "../../../controller/teacher/sidebar.php"; ?>
        <div class="main-content">
            <header class="topbar">
                <button class="menu-toggle"><i class="fas fa-bars"></i></button>
                <div class="topbar-middle">
                    <b>ALL HANDLED CLASSES</b>
                </div>
            </header>
            <main class="dashboard-content">
                <div class="card">
                    <form method="POST" class="filter">
                        <div class="filter-container">
                            <label for="filterYear" class="form-label">Filter by School Year:</label>
                            <select class="form-select" id="filterYear" name="filterSy_th">
                                <option disabled hidden selected value="">Choose School Year</option>
                                <?php echo schoolyear(); ?>
                            </select>
                        </div>
                        <div class="filter-container">
                            <label for="filterGrade" class="form-label">Filter by Grade Level:</label>
                            <select class="form-select" id="filterGrade" name="filterGrade_th">
                                <option disabled hidden selected value="">Choose Grade Level</option>
                                <?php echo gradelevel(); ?>
                            </select>
                        </div>
                        <div class="button-container">
                            <button type="button" id="search-button" class="btn btn-secondary" name="btnSy_th">Filter</button>
                            <button type="button" id="reset-button" class="btn btn-outline-secondary">Reset</button>
                        </div>
                    </form>
                    <hr>
                    <div class="table-location limit" >
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>School Year</th>
                                    <th>Grade Level</th>
                                    <th>Section</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="handled-table-body">
                               <?php echo handledclass() ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="../../../model/function.js?????"></script>
    <script>
       document.getElementById("search-button").addEventListener("click", function() {
             const SY = document.getElementById("filterYear").value.toLowerCase();
             const GL = document.getElementById("filterGrade").value.toLowerCase();
             filterTable("handled-table-body", SY, GL, 0, 1);
       });
       document.getElementById("reset-button").addEventListener("click", function() {
            document.getElementById("filterYear").value = '';
            document.getElementById("filterGrade").value = '';
            filterTable("handled-table-body", '', '', 0, 1);
        });
	</script>
    <script src="../../../assets/script/script.js"></script>
</body>
</html>
