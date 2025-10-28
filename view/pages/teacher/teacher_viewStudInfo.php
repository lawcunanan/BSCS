<?php 
require "../../../controller/teacher/main.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
     <link rel="shortcut icon" href="../../../model/picture/Logo_1.png" />
    <title>Student Information</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
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
                    <b>STUDENT INFORMATION</b>
                </div>
            </header>
            <main class="dashboard-content">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-bordered tab">
                            <thead class="table-secondary" style="text-align: center">
                                <tr>
                                    <th>FIELD</th>
                                    <th>INFORMATION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php echo studentinfo();?>
                            </tbody>
                        </table>
                    </div>
                    <hr />
                    <h2>CLASS AND SECTION HISTORY</h2>
                    <div class="table-location">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>School Year</th>
                                    <th>Grade Level</th>
                                    <th>Section</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php echo studentclasshistory();?>
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
