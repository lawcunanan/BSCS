<?php 
require "../../../controller/teacher/main.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="../../../model/picture/Logo_1.png" />
    <title>Grade Submission</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../../assets/style/style.css??" />
    <link rel="stylesheet" href="../../../assets/style/search.css???" />
    <link rel="stylesheet" href="../../../assets/style/modalstyle.css????">
    <style>
        .grade-input {
            width: 20%;
            text-align: center;
        }

        .search-form {
            gap: 15px;
        }

        .form-control{
            padding:    7px;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <?php include_once "../../../controller/teacher/sidebar.php"; ?>
        <div class="main-content">
            <header class="topbar">
                <button class="menu-toggle"><i class="fas fa-bars"></i></button>
                <div class="topbar-middle">
                    <b>ONLINE SUBMISSION OF GRADES (S.Y. <?php echo $school_year ?>)</b>
                </div>
            </header>
            <main class="dashboard-content">
                <div class="card">
                    <h2>GRADE <?php echo $grade ?> - <?php echo $section ?></h2>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Upload Grades</label>
                            <input class="form-control" type="file" id="formFile" name="upload_excel" accept=".xls,.xlsx" required />
                        </div>
                        <button type="submit" class="btn btn-success" name="btn_Excel">Upload</button>
                    </form>
                    <?php echo previewgrades(); ?>
                    <hr />
                    <h2>CLASS GRADES PREVIEW</h2>
                    <form method="POST" class="search-form">
                        <div class="search-container">
                            <label for="filter-input" class="form-label">Filter by Quarter</label>
                            <select class="form-select" id="filter-input" name="filterQuarter" onchange="this.form.submit()">
                                <option disabled hidden selected>Select Quarter</option>
                                <option value="1">1st Quarter</option>
                                <option value="2">2nd Quarter</option>
                                <option value="3">3rd Quarter</option>
                                <option value="4">4th Quarter</option>
                            </select>
                        </div>
                        <div class="search-container">
                            <label for="search-input" class="form-label">Search</label>
                            <input type="text" id="search-input" class="search-input" placeholder="Search by LRN or Student Name" name="filterSearch" />
                            <i class="fas fa-search search-icon"></i>
                        </div>
                    </form>
                    <hr />
                    <h2>Per Quarter Preview</h2>
                    <div class="table-location">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>LRN</th>
                                    <th>Student Name</th>
                                    <th>Sex</th>
                                    <th>Quarter</th>
                                    <th>Quarterly General Average</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="quarter-table-body">
                                <?php echo perquarter(); ?>
                            </tbody>
                        </table>
                    </div>
                    <h2>General Average Only</h2>
                    <div class="table-location">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>LRN</th>
                                    <th>Student Name</th>
                                    <th>Sex</th>
                                    <th>General Average</th>
                                    <th>Quarter</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="general-table-body">
                                <?php echo generalave(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="../../../model/function.js"></script>
    <script>
        const studentData = <?php echo json_encode($studentData); ?>;
        console.log(studentData);
        if (studentData.Handled.type === 'student_Grades' && studentData.Data) {
            previewGrades(studentData); 
        }
        getCurrentDate("date");
    </script>
    <?php  
        if (isset($studentData['Handled']['type'])) {
            if ($studentData['Handled']['type'] === 'update_Grade') {
                echo alert("<script>showalert('primary', '<strong>Submission Successful</strong> <br/> <br/> Student grades have been submitted.');</script>");
            } else if ($studentData['Handled']['type'] === 'notmatch_Grade') {
                echo alert("<script>showalert('danger', '<strong>Mismatch</strong> <br/> <br/> The School Year, Grade, or Section, in the Excel file doesn\'t match the batch.');</script>");
            } else if ($studentData['Handled']['type'] === 'handle_Grade') {
                echo alert("<script>showalert('danger', '<strong>Please Check</strong> <br/> <br/> Please review the file for the School Year, Grade, and Section.');</script>");
            }
        }
    ?>
    <script>
        document.getElementById("search-input").addEventListener("input", function() {
            searchTable("quarter-table-body", "search-input");
            searchTable("general-table-body", "search-input");
        });
    </script>
    <script src="../../../assets/script/script.js"></script>
</body>
</html>
