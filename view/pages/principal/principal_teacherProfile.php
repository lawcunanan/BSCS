
<?php 
require "../../../controller/principal/main.php";
 list($schoolyear, $output) = advisoryclasses();
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		 <link rel="shortcut icon" href="../../../model/picture/Logo_1.png" />
		<title>Teacher Profile</title>

		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
		<script src="https://kit.fontawesome.com/30f0c448ea.js" crossorigin="anonymous"></script>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
		<link rel="stylesheet" href="../../../assets/style/style.css??" />
		<link rel="stylesheet" href="../../../assets/style/search.css???" />
		<style>
			.dashboard-content {
				flex: 1;
				display: flex;
				flex-direction: column;
			}

			.card-header:first-child{
				background-color:#e0f2fe
			}

			.card {
				background-color: #ffffff;
				border-radius: 8px;
				box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
				margin-bottom: 20px;
				font-size: 14px;
			}

			.profile-header {
				display: flex;
				align-items: center;
				padding: 20px;
				
			}

			.profile-image {
				width: 150px;
				height: 150px;
				border-radius: 5%;
				margin-right: 20px;
				object-fit: cover;
			}

			.profile-name {
				font-size: 24px;
				font-weight: bold;
				margin-bottom: 5px;
				color: #1e293b;
			}

			.profile-title {
				color: #64748b;
			}

			.contact-info i {
				width: 20px;
				color: #0369a1;
				font-size: 15px;
			}

			@media (max-width: 768px) {
				.sidebar {
					position: fixed;
					left: -240px;
					height: 100%;
					z-index: 1000;
				}
				.sidebar.active {
					left: 0;
				}
				.menu-toggle {
					display: block;
				}
			}
		</style>
	</head>

	<body>
		<div class="dashboard">
			<?php $cur = 'teacher' ; include_once  "../../../controller/principal/sidebar.php"; ?>
			<div class="main-content">
				<header class="topbar">
					<button class="menu-toggle"><i class="fas fa-bars"></i></button>
					<div class="topbar-middle">
						<b>TEACHER PROFILE</b>
					</div>
				</header>
				<main class="dashboard-content">
					<div class="row">
						<div class="col-lg-8">
							<div class="card">
								<?php echo profile();?>
							</div>
							<div class="card">
								<h2 class="card-header">
									Advisory Class(es) for <?php  echo 'S.Y. ' . $schoolyear ?>
								</h2>
								<div class="table-location">
									<table class="table table-bordered">
										<thead class="table-secondary">
											<tr>
												<th>Grade Level</th>
												<th>Section</th>
												<th>Number of Students</th>
												<th>Actions</th>
											</tr>
										</thead>
										<tbody>
											<?php  echo $output; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="card">
								<h2 class="card-header">Personal Details</h2>
								 <?php echo profiledetails();?>
							</div>
						</div>
					</div>
				</main>
			</div>
		</div>

		<script src="../../../assets/script/script.js"></script>
	</body>
</html>
