<?php 
require "../../../controller/registrar/main.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="shortcut icon" href="../../../model/picture/Logo_1.png" />
    <title>Document Release Tracker</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../../assets/style/style.css">
    <link rel="stylesheet" href="../../../assets/style/search.css???????">
</head>

<body>
    <div class="dashboard">
        <?php $cur = 'tracker'; include_once "../../../controller/registrar/sidebar.php"; ?>
       
        <div class="main-content">
            <header class="topbar">
                <button class="menu-toggle"><i class="fas fa-bars"></i></button>
                <div class="topbar-middle">
                    <b>DOCUMENT RELEASE TRACKER</b>
                </div>
            </header>
            <main class="dashboard-content">
                <div class="card">
                    <form action="" method="POST" class="search-form mb-3">
                        <div class="search-container">
                            <label for="filterDocu" class="form-label">Filter by Document</label>
                            <select class="form-select" id="filterDocu" name="filterDocu">
                                <option value="" selected>Select Document Type</option>
                                <option>SF10 (Form 137)</option>
                                <option>Good Moral</option>
                                <option>Certificate of Enrollment</option>
                            </select>
                        </div>
                        <div class="search-container">
                            <label for="search-input" class="form-label">Search</label>
                            <input type="text" id="search-input" class="search-input" placeholder="Search by LRN or student name" name="search_Tr">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                    </form>
                    <hr>
                    <h2>RELEASED DOCUMENTS</h2>
                    <div class="table-location">
                        <table class="table table-bordered">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Date</th>
                                    <th>LRN</th>
                                    <th>Student Name</th>
                                    <th>Sex</th>
                                    <th>Released Document</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody id="studentlist-table-body">
                                <?php echo trackerdoc(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="../../../model/function.js"></script>
    <script>
        document.getElementById("search-input").addEventListener("input", function() {
            searchTable("studentlist-table-body", "search-input");
        });
        
        document.getElementById("filterDocu").addEventListener("change", function() {
            const doc = document.getElementById("filterDocu").value.toLowerCase();
            filterTable("studentlist-table-body", doc, '', 4, null);
        });
    </script>
    <script src="../../../assets/script/script.js"></script>
</body>
</html>
