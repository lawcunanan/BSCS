<?php
   require "../../../controller/admin/main.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="shortcut icon" href="../../../model/picture/Logo_1.png" />
    <title>Admin</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../../assets/style/style.css">
    <link rel="stylesheet" href="../../../assets/style/sidebar.css">
    <link rel="stylesheet" href="../../../assets/style/modalstyle.css"> 
    <link rel="stylesheet" href="../../../assets/style/forms.css">
     
    <style>
        
        
        .button-container {
            display: flex;
            justify-content: right;
            margin-top: 40px;
        }

        .logo-preview-container {
            width: 150px;
            height: 150px;
            border: 2px dashed #ccc;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 10px;
        }

        .logo-preview {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .logo-placeholder {
            color: #666;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar code remains the same -->
          <?php $cur = 'school' ; include_once  "../../../controller/admin/sidebar.php"; ?>
        <div class="main-content">
            <header class="topbar">
                <button class="menu-toggle"><i class="fas fa-bars"></i></button>
                <div class="topbar-middle">
                    <b>SCHOOL INFORMATION</b>
                </div>
            </header>
            <main class="dashboard-content">
                <div class="card">
                    <form action="" method="POST" enctype="multipart/form-data" class="was-validated">
                       
                         <?php echo schoolinformation();?>
                        <div class="button-container">
                            <button type="button" id="edit-button" class="btn btn-primary" name = "saveinformation">Edit Information</button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script src="../../../assets/script/script.js"></script>
    <script>
        document.getElementById('schoolLogo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('logoPreview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } 
        });

        document.getElementById('edit-button').addEventListener('click', function() {
            const button = this;
            const inputs = document.querySelectorAll('input, select');
            
            if (button.textContent === 'Edit Information') {
                button.textContent = 'Save Information';
                button.classList.remove('btn-primary');
                button.classList.add('btn-success');
                inputs.forEach(input => input.disabled = false);
                button.type = 'submit';
            }
        });
    </script>
</body>
</html>