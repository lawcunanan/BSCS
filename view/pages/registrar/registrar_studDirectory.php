<?php 
require "../../../controller/registrar/main.php";
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		 <link rel="shortcut icon" href="../../../model/picture/Logo_1.png" />
		<title>Student Directory</title>

		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
		<link rel="stylesheet" href="../../../assets/style/style.css" />
		<link rel="stylesheet" href="../../../assets/style/search.css??????/" />
		<style>
			.button-container {
				display: flex;
				justify-content: right;
			}
		</style>
	</head>
	<body>
		<div class="dashboard">
			<?php $cur = 'studdirectory'; include_once "../../../controller/registrar/sidebar.php"; ?>
			
			<div class="main-content">
				<header class="topbar">
					<button class="menu-toggle"><i class="fas fa-bars"></i></button>
					<div class="topbar-middle">
						<b>STUDENT DIRECTORY</b>
					</div>
				</header>
				<main class="dashboard-content">
					<div class="card">
						<div method="POST">
							<form method="POST" class="search-form mb-3">
								<div class="search-container">
									<label for="filterStatus" class="form-label">Filter by Status</label>
									<select class="form-select" id="filterStatus" name="filterStatus_sd">
										<option value="" selected>Select Status</option>
										<?php echo status(); ?>
									</select>
								</div>
								<div class="search-container">
									<label for="search-input" class="form-label">Search</label>
									<input type="text" id="search-input" class="search-input" placeholder="Search by LRN or student name" />
									<i class="fas fa-search search-icon"></i>
								</div>
							</form>
						</div>
						<hr />
						<div class="table-location">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>LRN</th>
										<th>Student Name</th>
										<th>Sex</th>
										<th>Age</th>
										<th>Status</th>
										<th>Remarks</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody id="directory-table-body">
									<?php echo studentlist(); ?>
								</tbody>
							</table>
						</div>
					</div>
				</main>
			</div>
		</div>
        <script src="../../../model/function.js?????"></script>
		<script>
			document.getElementById("search-input").addEventListener("input", function() {
				searchTable("directory-table-body", "search-input");
			});	
			
			document.getElementById("filterStatus").addEventListener("change", function() {
				const stat = document.getElementById("filterStatus").value.toLowerCase();
				filterTable("directory-table-body", stat, '', 4, null);
			});
		</script>
		<script src="../../../assets/script/script.js"></script>
	</body>
</html>
