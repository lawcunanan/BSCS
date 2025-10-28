
<?php
   require "../../../controller/admin/main.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="shortcut icon" href="../../../model/picture/Logo_1.png" />
    <title>Manage Accounts</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../../assets/style/style.css">
    <link rel="stylesheet" href="../../../assets/style/search.css">
    <link rel="stylesheet" href="../../../assets/style/modalstyle.css"> 
     <link rel="stylesheet" href="../../../assets/style/forms.css"> 
    <style>
      
        .image-upload {
            display: flex;
            align-items: flex-start;
            gap: 20px;
        }

        .preview-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }
        .modal-body{
            display: flex;
            flex-direction: column;
            gap: 5px;

        }
        .form-section input, .form-section select{
          padding: 10px;

        } 

    </style>
</head>
<body>
    <div class="dashboard">
        <?php $cur = 'index' ; include_once  "../../../controller/admin/sidebar.php"; ?>
        <div class="main-content">
            <header class="topbar">
                <button class="menu-toggle"><i class="fas fa-bars"></i></button> 
                <div class="topbar-middle">
                    <b>MANAGE ACCOUNTS</b>
                </div>
            </header>
           
            <main class="dashboard-content">
                <div class="card">
                    <form action="" method="POST" class="search-form">
                        <div class="search-container">
                            <label for="search-input" class="form-label">Search</label>
                            <input type="text" id="search-input" class="search-input" placeholder="Search by ID, name, or role">
                            <i class="fas fa-search search-icon"></i>
                        </div> 
                    </form>
                    <hr>
                    <button id="create-account-btn" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#accountModal" onclick="setProvince('province')">Create New Account</button>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-secondary">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="userlist-table-body">
                                <?php echo manageacc(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
     <?php echo createacc(); ?>
  
 <script src = "../../../model/function.js??????"></script>
    <script> 
    function openModal(select, modalId) {
   
        var modalElement = document.getElementById(modalId);
        var bootstrapModal = new bootstrap.Modal(modalElement);
        bootstrapModal.show();
   
        }
    </script>

   <script>
    getCurrentDate("date");
        document.getElementById("search-input").addEventListener("input", function() {
            searchTable("userlist-table-body", "search-input");
        });
    </script>
   
</body>
</html>