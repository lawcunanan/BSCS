<?php 
require "../../../controller/admin/main.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../assets/images/BSCS-logo.png" />
    <title>Enroll Class</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="../../../assets/style/style.css">
    <link rel="stylesheet" href="../../../assets/style/search.css">
    <style>
			.card {
				height: calc(100vh - 100px); 
				display: flex;
				flex-direction: column;
				padding: 20px;
			}
			
			.content-wrapper {
				display: flex;
				flex: 1;
				overflow: hidden;
				font-size: 14px;
			}
			
			.alphabet-filter {
				display: flex;
				flex-direction: column;
				margin-right: 20px;
				overflow-y: auto;
			}

			.alphabet-filter a {
				padding: 5px 10px;
				color: black;
				text-decoration: none;
				border-radius: 4px;
				transition: all 0.3s ease;
			}

			.alphabet-filter a:hover {
				background-color: #0284c7;
				color: white;
			}

			.vertical-divider {
				border: none;
				border-left: 1px solid #e2e8f0;
				height: auto;
				margin: 0 20px;
			}

			.files-section {
				flex: 1;
				overflow: hidden;
			}

			.files-content {
				height: 100%;
				overflow-y: auto;
				padding-right: 10px;
			}

			.file-list {
				list-style-type: none;
				padding: 0;
			}

			.file-list li {
				margin-bottom: 10px;
			}

			.file-list a {
				display: block;
				padding: 10px;
				background-color: #f1f5f9;
				border-radius: 4px;
				text-decoration: none;
				color: #1e293b;
				transition: all 0.3s ease;
			}

			.file-list a:hover {
				background-color: #e0f2fe;
				color: #0284c7;
			}

			.letter-nav {
				font-size: 1.5rem;
				text-decoration: none;
				color: darkgrey;
				display: block;
				margin-top: 20px;
				margin-bottom: 10px;
			}
				
			.alphabet-filter::-webkit-scrollbar,
			.files-section::-webkit-scrollbar {
				width: 4px; 
			}

			.alphabet-filter::-webkit-scrollbar-track,
			.files-section::-webkit-scrollbar-track {
				background: transparent;
			}

			.alphabet-filter::-webkit-scrollbar-thumb,
			.files-section::-webkit-scrollbar-thumb {
				background-color: rgba(0, 0, 0, 0.05); 
				border-radius: 2px;
			}

			.alphabet-filter::-webkit-scrollbar-thumb:hover,
			.files-section::-webkit-scrollbar-thumb:hover {
				background-color: rgba(
					0,
					0,
					0,
					0.1
				); 
			}

			.alphabet-filter,
			.files-section {
				scrollbar-width: thin;
				scrollbar-color: rgba(0, 0, 0, 0.05) transparent;
			}
	</style>
</head>

<body>
    <div class="dashboard">
        <?php $cur = 'file' ; include_once  "../../../controller/admin/sidebar.php"; ?>
        <div class="main-content">
            <header class="topbar">
                <button class="menu-toggle"><i class="fas fa-bars"></i></button> <!-- Collapse Sidebar -->
                <div class="topbar-middle">
                    <b>FILE DIRECTORY</b>
                </div>
            </header>
            <main class="dashboard-content">
                <div class="card">
                    <div class="search-section">
                        <form method="POST" class="search-form">
                            <div class="search-container">
                                <label for="search-input" class="form-label">Search</label>
                                <input type="text" id="search-input" class="search-input" placeholder="Search by file name">
                                <i class="fas fa-search search-icon"></i>
                            </div>
                        </form>
                    </div>
                    <div class="content-wrapper">
                        <div class="alphabet-filter">
                            <a href="#A">A</a>
                            <a href="#B">B</a>
                            <a href="#C">C</a>
                            <a href="#D">D</a>
                            <a href="#E">E</a>
                            <a href="#F">F</a>
                            <a href="#G">G</a>
                            <a href="#H">H</a>
                            <a href="#I">I</a>
                            <a href="#J">J</a>
                            <a href="#K">K</a>
                            <a href="#L">L</a>
                            <a href="#M">M</a>
                            <a href="#N">N</a>
                            <a href="#O">O</a>
                            <a href="#P">P</a>
                            <a href="#Q">Q</a>
                            <a href="#R">R</a>
                            <a href="#S">S</a>
                            <a href="#T">T</a>
                            <a href="#U">U</a>
                            <a href="#V">V</a>
                            <a href="#W">W</a>
                            <a href="#X">X</a>
                            <a href="#Y">Y</a>
                            <a href="#Z">Z</a>
                        </div>
                        <hr class="vertical-divider">
                        <div class="files-section">
                            <div class="files-content">
                                <a class="letter-nav" id="A">A</a>
                                <ul class="file-list">
                                    <li><a href="#">Annual Report 2023</a></li>
                                    <li><a href="#">Academic Calendar</a></li>
                                </ul>
                                <a class="letter-nav" id="B">B</a>
                                <ul class="file-list">
                                    <li><a href="#">Break It Down Yo!</a></li>
                                    <li><a href="#">Budget Request</a></li>
                                    <li><a href="#">Bill of Rights</a></li>
                                </ul>
                                <a class="letter-nav" id="C">C</a>
                                <ul class="file-list">
                                    <li><a href="#">Constitution</a></li>
                                    <li><a href="#">Credits</a></li>
                                    <li><a href="#">Central Directory</a></li>
                                    <li><a href="#">Collection of ChuChu</a></li>
                                </ul>
                                <a class="letter-nav" id="D">D</a>
                                <ul class="file-list">
                                    <li><a href="#">Data Management Memo</a></li>
                                    <li><a href="#">Directory of Files</a></li>
                                    <li><a href="#">Dingding</a></li>
                                    <li><a href="#">Deadline for Activity Report</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="../../../assets/script/script.js"></script>
</body>
</html>