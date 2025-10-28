<?php
require "../../../controller/security/main.php";
unset($_SESSION['otp_code']);	
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign In</title>
     <link rel="shortcut icon" href="../../../model/picture/Logo_1.png" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../../assets/style/styleSecurity.css????????" />
</head>
<body>
    <main>
        <section class="lrCon">
            <div class="leftCon" >
                <div class="logo-container">
                    <img src="../../../assets/images/BSCS-logo.png" alt="" />
                </div>
                <div style="text-align: center;">
                    <h1>INFORMATION</h1>
                    <h1>SYSTEM</h1>
                </div>
            </div>

            <div class="rightCon">
                <form id="myform"  method="POST" class="was-validated">
                    <h1>Sign In</h1>
                    <p>Sign in to access your BSCS account</p>

                    <section>
                        <div class="fieldgroup">
                            <label for="email">Email</label><br />
                            <input type="email" id="email" class="form-control field" name="email" placeholder="@gmail.com" required />
                        </div>

                        <div class="fieldgroup">
                            <label for="password">Password</label><br />
                            <input type="password" id="password" class="form-control field" name="password"  placeholder="password" pattern="^^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$" title="At least 8 characters long. Must include one uppercase letter, one lowercase letter, one number, and one special character." required />
                        </div>

                        <a href="forget.php" class="forget">Forget password?</a>

                        <button id="btnSignin" class="field" name="btnSignin">
                            Sign In
                        </button>
                    </section>
                </form>
            </div>
        </section>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
