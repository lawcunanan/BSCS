<?php 
require "../../../controller/registrar/main.php";
$sy = registerSY();
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
    <link rel="stylesheet" href="../../../assets/style/style.css">
    <link rel="stylesheet" href="../../../assets/style/search.css">
    <link rel="stylesheet" href="../../../assets/style/modalstyle.css??????????">
</head>

<body>
    <div class="dashboard">
        <?php $cur = 'enroll'; include_once "../../../controller/registrar/sidebar.php"; ?>
        <div class="main-content">
            <header class="topbar">
                <button class="menu-toggle"><i class="fas fa-bars"></i></button>
                <div class="topbar-middle">
                    <b>ENROLL CLASS</b>
                </div>
            </header>
            <main class="dashboard-content">
                <div class="card">
                    <h2>UPLOAD CLASS LIST (SF1) <i>(.xls , .xlsx)</i></h2>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <input class="form-control" type="file" id="formFile" name="upload_excel" accept=".xls,.xlsx" required>
                        </div>
                        <button type="submit" class="btn btn-success" name="btn_Excel" value="<?php echo $sy?>">Upload</button>
                    </form>
                    <?php 
                        echo previewenroll();
                        echo enrolledbacth();
                    ?>
                    <hr>
                    <h2>CURRENT ENROLLED CLASSES (S.Y. <?php echo $sy?>)</h2>
                    <div class="table-location">
                        <table class="table table-bordered">
                            <thead class="table-secondary">
                                <tr>
                                    <th>School Year</th>
                                    <th>Grade Level</th>
                                    <th>Section</th>
                                    <th>Remarks</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php echo registerclass(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="../../../model/function.js????"></script>
    <script>
        const studentData = <?php echo json_encode($studentData); ?>;
        console.log(studentData);
        if (studentData.Handled.type === 'preview_Enroll' && studentData.Data) {
            preview_uploadexcel(studentData);  
        } else if (studentData.Handled.type === 'studentpending_Enroll') {
            preview_pendingexcel(studentData);  
        }
        getCurrentDate("date");
    </script>
    <?php  
        if ($studentData) {
            if ($studentData['Handled']['type'] == 'student_Enroll') {
                echo alert("<script>showalert('primary', '<strong>Enrollment Successful</strong> <br/> <br/> New students have been successfully enrolled from the uploaded Excel file.');</script>");
            } else if ($studentData['Handled']['type'] == 'update_Enroll') {
                echo alert("<script>showalert('primary', '<strong>Information Update</strong> <br/> <br/> Student details have been refreshed with the latest information.');</script>");
            } else if ($studentData['Handled']['type'] == 'handle_Enroll') {
                echo alert("<script>showalert('danger', '<strong>Please Check</strong> <br/> <br/> Please review the file for the School Year, Grade, and Section.');</script>");
            } else if ($studentData['Handled']['type'] == 'schoolyear_Enroll') {
                echo alert("<script>showalert('danger', '<strong>Notice</strong> <br/> <br/> You cannot enroll a student who is already enrolled in the same school year.');</script>");
            } 
        }
    ?>
    <script src="../../../assets/script/script.js"></script>
</body>
</html>
