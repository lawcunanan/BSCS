<?php 
require "../../../controller/principal/main.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
     <link rel="shortcut icon" href="../../../model/picture/Logo_1.png" />
    <title>Generate Documents</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../../assets/style/style.css???????">
    <link rel="stylesheet" href="../../../assets/style/search.css??" />
    <link rel="stylesheet" href="../../../assets/style/modalstyle.css??????">
    <link rel="stylesheet" href="../../../assets/style/generate.css?????????">
    <style>
        .limit{
            height: 425px;
        }
    </style>
</head>
 
<body>
    <div class="dashboard">
        <?php $cur = 'generate'; include_once "../../../controller/principal/sidebar.php"; ?>
        <div class="main-content">
            <header class="topbar">
                <button class="menu-toggle"><i class="fas fa-bars"></i></button>
                <div class="topbar-middle">
                    <b>GENERATE A DOCUMENT</b>
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
                        <div class = "limit" >
                            <table class="table table-bordered text-center">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>Select</th>
                                        <th>LRN</th>
                                        <th>Student Name</th>
                                        <th>Sex</th>
                                        <th>Age</th>
                                    </tr>
                                </thead>
                                <tbody id="studentlist-table-body">
                                    <?php echo generatedoc(); ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="search-container mt-2 print" style="display: flex; gap: 20px;">
                            <select class="form-select" id="docuType" name="docuType" style="width: 30%;" required>
                                <option value="" disabled hidden selected>Select a document to generate</option>
                                <?php echo documents(); ?>
                            </select>
                            <button type="submit" id="generate-button" name="btn_Generate" class="btn btn-success">
                                <i class="fa fa-file"></i> Generate
                            </button>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#myTemplate">
                                Template
                            </button>
                        </div>
                    </form>
                    <?php if (isset($_POST['docuType'])) { ?>
                        <hr class="print">
                        <h2 class="print">DOCUMENT PREVIEW</h2>
                        <div class="preview-location">
                            <button type="button" id="print-button" class="btn btn-secondary print" data-bs-toggle="modal" data-bs-target="#printModal"><i class="fa fa-print"></i> Release Document</button>
                            <div class="main">
                                <?php 
                                if ($_POST['docuType'] !== "3") {
                                    echo $_SESSION['Document'] = gooodmDocumentPreview();
                                } else {
                                    echo $_SESSION['Document'] = sfDocumentPreview();
                                }   
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php $doc = isset($_POST['docuType']) ? $_POST['docuType'] : ''; echo request($doc); ?>
                </div>
                <?php echo edittemplate(); echo templatedelete(); ?>
            </main>
        </div>
    </div>
    
    <script src="../../../model/function.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        updateButtonName("btnDelete Template", "btnDeleteTemplate");
        getCurrentDate("date");
        document.getElementById("btnUpdate").addEventListener("click", function () {
            const userMessage = document.getElementById("editor").innerHTML;
            document.getElementById("certificateContent").innerHTML = userMessage;
        });
        var arr = <?php echo json_encode(doctemplate()); ?>;
        function selectTemplate(id) {
            document.getElementById("editor").innerHTML = arr[id];
            document.getElementById("certificateContent").innerHTML = arr[id];
        }
    </script>
    <script>
        document.getElementById("search-input").addEventListener("input", function() {
            searchTable("studentlist-table-body", "search-input");
        });
    </script>
    <?php echo generatePdfScript(); ?>
    <script src="../../../assets/script/script.js"></script>
</body>
</html>
