<?php 
require "../../../controller/principal/main.php";
list($output, $teacher) = manageclass();
$jsonData = json_encode(teacherslist());
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
     <link rel="shortcut icon" href="../../../model/picture/Logo_1.png" />
    <title>Class List</title>
	
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../../assets/style/style.css???" />
    <link rel="stylesheet" href="../../../assets/style/search.css???" />
    <link rel="stylesheet" href="../../../assets/style/modalstyle.css??"/>
    <style>
        .modal-dialog {
            max-width: 800px;
        }
        
        .modal-content {
            height: 95vh; 
            display: flex;
            flex-direction: column;
        }

        .modal-body {
            flex-grow: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .scrollable-container {
            overflow-y: auto;
            flex-grow: 1;
        }

        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            padding-right: 15px; 
        }

        .card {
            width: 100%;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            margin-bottom: 0;
        }

        .card-img-top {
            width: 100%;
            height: 230px;
            object-fit: cover;
        }

        .card-body {
            padding: 15px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .card-title {
            font-size: 16px;
            line-height: 1.2;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #1e293b;
            text-align: center;
        }

        .card-text {
            font-size: 13px;
            line-height: 1.4;
            color: #64748b;
            flex-grow: 1;
            text-align: center;
            color: red;
        } 

        @media (max-width: 768px) {
            .card-container {
                grid-template-columns: 1fr;
            }
        }

        
        .search-form {
            display: flex;
            align-items: flex-end;
            gap: 15px;
        }
    </style>
    </style>
</head>
<body>
    <div class="dashboard">
        <?php include_once "../../../controller/principal/sidebar.php"; ?>
        <div class="main-content">
            <header class="topbar">
                <button class="menu-toggle"><i class="fas fa-bars"></i></button>
                <div class="topbar-middle">
                    <b><?php echo "CLASS LIST: GRADE {$grade} - {$section} (S.Y.  {$school_year})" ?></b>
                </div>
            </header>
            <main class="dashboard-content">
                <div class="card">
                    <?php if (isset($teacher)) { ?>
                    <div class="search-form">
                        <div class="search-container">
                            <label for="adviser" class="form-label">Adviser</label>
                            <input type="text" class="search-input" id="adviser" name="adviser" value="<?php echo $teacher; ?>" disabled />
                        </div>
                        <div class="button-container">
                            <button type="button" id="search-button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#adviserModal">Change</button>
                        </div>
                    </div>
                    <?php } else { ?>
                    <div class="search-form">
                        <div class="search-container">
                            <label for="adviser" class="form-label">Adviser</label>
                            <input type="text" class="search-input" id="adviser" name="adviser" value="No adviser yet" disabled />
                        </div>
                        <div class="button-container">
                            <button type="button" id="search-button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#adviserModal">Set Adviser</button>
                        </div>
                    </div>
                    <?php } ?>
                    <hr />
                    <h2>ALL STUDENTS</h2>
                    <form method="POST" class="search-form">
                        <div class="search-container">
                            <label for="search-input" class="form-label">Search</label>
                            <input type="text" id="search-input" class="search-input" placeholder="Search by LRN or student name" name="search_vst" />
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
                                    <th>General Avg.</th>
                                    <th>Remarks</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="studentlist-table-body">
                                <?php echo $output ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php echo teacherlist1(); ?>
    <script src="../../../model/function.js"></script>
    <script>
        getCurrentDate("date");
        populateTeacherModal(<?php echo $jsonData ?>);    
        updateButtonName("btnSelect as Adviser", "btnSelectasAdviser");
    </script>
    <script>
        document.getElementById("search-input").addEventListener("input", function() {
            searchTable("studentlist-table-body", "search-input");
        });
    </script>
    <script src="../../../assets/script/script.js"></script>
</body>
</html>
