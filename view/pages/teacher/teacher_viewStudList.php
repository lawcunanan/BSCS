<?php 
require "../../../controller/teacher/main.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="../../../model/picture/Logo_1.png" />
    <title>Class List</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../../assets/style/style.css" />
    <link rel="stylesheet" href="../../../assets/style/search.css" />
</head>
<body>
    <div class="dashboard">
        <?php include_once "../../../controller/teacher/sidebar.php"; ?>
        
        <div class="main-content">
            <header class="topbar">
                <button class="menu-toggle"><i class="fas fa-bars"></i></button>
                <div class="topbar-middle">
                    <b>CLASS LIST: GRADE <?php echo $grade ?> - <?php echo $section ?> (S.Y. <?php echo $school_year ?>)</b>
                </div>
            </header>
            <main class="dashboard-content">
                <div class="card">
                    <h2>ALL STUDENTS</h2>
                    <form method="POST" class="search-form">
                        <div class="search-container">
                            <label for="search-input" class="form-label">Search</label>
                            <input type="text" id="search-input" class="search-input" placeholder="Search by LRN or Student Name" />
                            <i class="fas fa-search search-icon"></i>
                        </div>
                    </form>
                    
                    <hr />
                    <div class="table-location">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>LRN</th>
                                    <th>Student Name</th>
                                    <th>Sex</th>
                                    <th>Age</th>
                                    <th>General Avg</th>
                                    <th>Remarks</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="studentlist-table-body">
                                <?php echo studentlist();?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="../../../model/function.js"></script>
    <script>
        document.getElementById("search-input").addEventListener("input", function() {
            searchTable("studentlist-table-body", "search-input");
        });		
    </script>
    <script src="../../../assets/script/script.js"></script>
</body>
</html>
