<?php 
require "../../../controller/registrar/main.php";
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
	<link rel="stylesheet" href="../../../assets/style/modalstyle.css" />
</head>

<body>
	<div class="dashboard">
		<?php include_once "../../../controller/registrar/sidebar.php"; ?>
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
							<thead class="table-secondary">
								<tr>
									<th>FIELD</th>
									<th>INFORMATION</th>
								</tr>
							</thead>
							<tbody>
								<?php echo studentinfo(); ?>
							</tbody>
						</table>
					</div>
					<hr />
					<h2>REQUIRED DOCUMENTS</h2>
					<div class="table-location">
						<table class="table table-bordered">
							<thead>
								<tr class="table-secondary">
									<th>Document Type</th>
									<th>Remarks</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php echo studentrequirements(); ?>
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
								<?php echo studentclasshistory(); ?>
							</tbody>
						</table>
					</div>
				</div>
			</main>
			<?php 
			echo displayModalsreq();
			echo previewgrades1();
			?>
		</div>
	</div>
	<script src="../../../model/function.js"></script>
	<script>
		getCurrentDate("date");
		updateButtonName("btnUpdate Requirement", "btnUpdateReq");
		updateButtonName("btnUpload Requirement", "btnUploadReq");
		updateButtonName("btnUpdate Transcript", "UpdateTran");
		updateButtonName("btnUpload Transcript", "UploadTran");
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
	if (isset($studentData['Handled']['type'])) {
		if ($studentData['Handled']['type'] === 'update_Grade') {
			echo alert("<script>showalert('primary', '<strong>Submission Successful</strong> <br/> <br/> Student grades have been submitted.');</script>");
		} else if ($studentData['Handled']['type'] === 'notmatch_Grade') {
			echo alert("<script>showalert('danger', '<strong>Mismatch</strong> <br/> <br/> The School Year, Grade, or Section, in the Excel file doesn\'t match the batch.');</script>");
		} else if ($studentData['Handled']['type'] === 'handle_Grade') {
			echo alert("<script>showalert('danger', '<strong>Please Check</strong> <br/> <br/> Please review the file for the School Year, Grade, and Section.');</script>");
		} elseif (!isset($studentData['Data'])) {
			echo alert("<script>showalert('danger', '<strong>Please Check</strong> <br/> <br/> This student is not included in the Excel list.');</script>");
		}
	}
	?>
	<script src="../../../assets/script/script.js"></script>
</body>
</html>

