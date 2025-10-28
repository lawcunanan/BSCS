<?php 
require "../../../controller/principal/main.php";
list($schoolyear, $output) = teacherlist();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
     <link rel="shortcut icon" href="../../../model/picture/Logo_1.png" />
    <title>View Teachers</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://kit.fontawesome.com/30f0c448ea.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../../assets/style/style.css" />
    <style>
        .dashboard-content {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
            gap: 20px;
            padding: 20px;
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
            font-size: 15px;
            line-height: 1.2;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #1e293b;
        }

        .card-text {
            font-size: 0.9rem;
            line-height: 12px;
            color: #64748b;
            flex-grow: 1;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .dashboard-content {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="dashboard">
        <?php include_once "../../../controller/principal/sidebar.php"; ?>
        <div class="main-content">
            <header class="topbar">
                <button class="menu-toggle"><i class="fas fa-bars"></i></button>
                <div class="topbar-middle">
                    <b>ALL TEACHERS (S.Y. <?php echo $schoolyear; ?>)</b>
                </div>
            </header>
            <main class="dashboard-content">
                <?php echo $output; ?>
            </main>
        </div>
    </div>
    <script src="../../../assets/script/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
