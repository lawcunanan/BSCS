<?php require "../../../controller/admin/main.php";
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		 <link rel="shortcut icon" href="../../../model/picture/Logo_1.png" />
		<title>Admin</title>

		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
		<link rel="stylesheet" href="../../../assets/style/style.css???" />
	</head>

	<body>
		<div class="dashboard">
			<?php $cur = 'index' ; include_once  "../../../controller/admin/sidebar.php"; ?>
			<div class="main-content">
				<header class="topbar">
					<div class="topbar-left">
						<button class="menu-toggle"><i class="fas fa-bars"></i></button>
						<span class="topbar-date" id="current-date"></span>
					</div>
					<div class="topbar-right">
						<div class="user-info">
							<span>Welcome, Admin <?php echo username(); ?></span>
							<div class="user-avatar">
								<img
									src="../../../model/picture/User_<?php echo $admin?>.png???"
									alt=""
								/>
							</div>
						</div>
					</div>
				</header>
				<main class="dashboard-content">
					<div class="card">
						<h2>NUMBER OF USERS</h2>
						<div class="table-location">
							<table class="table table-bordered">
								<thead class="table-secondary">
									<tr>
										<th>Position</th>
										<th>Total Number</th>
									</tr>
								</thead>
								<tbody>
								 <?php echo currentNumber() ?>
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
