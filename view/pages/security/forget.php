<?php
require "../../../controller/security/main.php";
forget_session();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forget Password</title>
     <link rel="shortcut icon" href="../../../model/picture/Logo_1.png" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../../assets/style/styleSecurity.css???" />
</head>
<body>
    <main>
        <section class="lrCon">
            <div class="leftCon">
                <div class="logo-container">
                    <img src="../../../assets/images/BSCS-logo.png" alt="BSCS Logo" />
                </div>
                <div style="text-align: center;">
                    <h1>INFORMATION</h1>
                    <h1>SYSTEM</h1>
                </div>
            </div>

            <div class="rightCon">
                <form id="myform" method="POST" class="was-validated">
                    <div class="group">
                        <a class="back" href="index.php">
                            <i class="bx bx-chevron-left"></i> Back to Sign in
                        </a>
                    </div>
                    <h1>Forget Password</h1>
                    <p>
                        Don’t worry, happens to all of us. Enter your email below to
                        recover your password.
                    </p>

                    <section>
                        <div class="fieldgroup">
                            <label for="email">Email</label> <br />
                            <input
                                type="email"
                                id="email"
                                class="form-control field"
                                name="email"
                                placeholder="@gmail.com"
                                required
                            />
                        </div>

                        <button id="btnForget" class="field" name="btnForget">
                            Submit
                        </button>
                    </section>
                </form>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
