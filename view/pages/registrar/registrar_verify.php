<?php 
require "../../../controller/registrar/main.php"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="shortcut icon" href="../../../model/picture/Logo_1.png" />
    <title>Verify Grades</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../../assets/style/style.css???????">
    <link rel="stylesheet" href="../../../assets/style/search.css">
    <link rel="stylesheet" href="../../../assets/style/modalstyle.css??">
    <link rel="stylesheet" href="../../../assets/style/generate.css??????">

    
</head>
<body>
    <div class="dashboard">
        <?php $cur = 'verify' ; include_once  "../../../controller/registrar/sidebar.php"; ?>
        
        <div class="main-content">
            <header class="topbar print">
                <button class="menu-toggle"><i class="fas fa-bars"></i></button>
                <div class="topbar-middle">
                    <b>Verify Grades</b>
                </div>
            </header>
            <main class="dashboard-content">
                <div class="card">
                    <form method="POST" class="search-form print">
                        <div class="search-container print">
                            <label for="search-input" class="form-label">Search</label>
                            <input type="text" id="search-input" class="search-input" placeholder="Search by LRN or student name" name="search_Ge">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                    </form>
                    <form method="POST" class="mt-3 print">
                        <span>Results:</span>
                        <div class="limit">
                            <table class="table table-bordered text-center">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>SF10</th>
                                        <th>LRN</th>
                                        <th>Student Name</th>
                                        <th>Sex</th>
                                        <th>Age</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="studlist-table-body">
                                    <?php echo generategradeverfify(); ?>
                                </tbody>
                            </table> 
                        </div> 
                    </form>
                    <?php if(isset($_POST['btn_Preview'])) { ?>
                    <hr class="print">
                    <h2 class="print">SF10 (Form 137) PREVIEW</h2>
                    <div class="preview-location">
                       <form method="POST" style="display: <?php echo (isset($_POST['btn_Preview']) && json_decode($_POST['btn_Preview'], true)[2] === 'Verified') ? 'none' : 'block'; ?>">
                            <button type="submit" name="completeGrade" id="print-button" value="<?php echo isset($_POST['btn_Preview']) ? htmlspecialchars(json_decode($_POST['btn_Preview'], true)[0]) : ''; ?>" class="btn btn-secondary print" data-bs-toggle="modal" data-bs-target="#printModal">
                                <i class="fa fa-print"></i> Complete Grade
                            </button>
                        </form>
                        <div class="main">
                            <?php echo sfDocumentPreview(); ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </main>
            <?php echo previewgrades1(); ?>
        </div>
    </div>
    <script src="../../../model/function.js??"></script>
    <script>
        getCurrentDate("date");
    </script>
    <script>
        const studentData = <?php echo json_encode($studentData); ?>;
        console.log(studentData);
        if (studentData.Handled.type === 'student_Grades' && studentData.Data) {
            previewGrades(studentData); 
        }
    </script>
    <?php  
        if(isset($studentData['Handled']['type'])) {
            if ($studentData['Handled']['type'] === 'update_Grade') {
                echo alert("<script>showalert('primary', '<strong>Submission Successful</strong> <br/> <br/> Student grades have been submitted.');</script>");
            } else if ($studentData['Handled']['type'] === 'notmatch_Grade') {
                echo alert("<script>showalert('danger', '<strong>Mismatch</strong> <br/> <br/> The School Year, Grade, or Section, in the Excel file doesn\'t match.');</script>");
            } else if($studentData['Handled']['type'] === 'handle_Grade') {
                echo alert("<script>showalert('danger', '<strong>Please Check</strong> <br/> <br/> Please review the file for the School Year, Grade, and Section.');</script>");
            } else if(!isset($studentData['Data'])) {
                echo alert("<script>showalert('danger', '<strong>Please Check</strong> <br/> <br/> This student is not included in the Excel list.');</script>");
            }
        }
    ?>
    <script>
        document.querySelectorAll('#btnUpdate').forEach(button => {
            button.setAttribute('name', 'btnSubmitgrade'); 
        });

         document.querySelectorAll('#btnUpdate_Grades').forEach(button => {
            button.setAttribute('name', 'btnUpdatedGrades'); 
        });


        document.getElementById("search-input").addEventListener("input", function() {
            searchTable("studlist-table-body", "search-input");
        });		
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="../../../assets/script/script.js"></script>
</body>
</html>
