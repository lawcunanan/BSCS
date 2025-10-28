<?php 
require "../../../controller/security/main.php";
verify_session();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verify</title>
     <link rel="shortcut icon" href="../../../model/picture/Logo_1.png" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../../assets/style/modalstyle.css" />
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
                <section class="verifyForm">
                    <form id="myform" method="POST">
                        <div class="group">
                            <a class="back" href="index.php">
                                <i class="bx bx-chevron-left"></i> Back to Sign in
                            </a>
                        </div>
                        <h1>Verify Code</h1>
                        <p>An authentication code has been sent to your email.</p>
                        <section>
                            <h1 class="timer"></h1>
                            <div class="fieldgroup">
                                <label for="password">Enter Code</label> <br />
                                <div class="fieldgrid">
                                    <input
                                        type="text"
                                        id="auth1"
                                        class="field"
                                        name="auth1"
                                        placeholder="0"
                                        pattern="\d"
                                        maxlength="1"
                                        title="Number only"
                                        required
                                        oninput="setFocus(this, 'auth2')"
                                        autofocus
                                    />

                                    <input
                                        type="text"
                                        id="auth2"
                                        class="field"
                                        name="auth2"
                                        placeholder="0"
                                        maxlength="1"
                                        title="Number only"
                                        required
                                        oninput="setFocus(this, 'auth3')"
                                    />

                                    <input
                                        type="text"
                                        id="auth3"
                                        class="field"
                                        name="auth3"
                                        placeholder="0"
                                        maxlength="1"
                                        title="Number only"
                                        required
                                        oninput="setFocus(this, 'auth4')"
                                    />

                                    <input
                                        type="text"
                                        id="auth4"
                                        class="field"
                                        name="auth4"
                                        placeholder="0"
                                        maxlength="1"
                                        title="Number only"
                                        required
                                        oninput="setFocus(this, 'auth5')"
                                    />

                                    <input
                                        type="text"
                                        id="auth5"
                                        class="field"
                                        name="auth5"
                                        placeholder="0"
                                        maxlength="1"
                                        title="Number only"
                                        required
                                        oninput="setFocus(this, 'auth6')"
                                    />

                                    <input
                                        type="text"
                                        id="auth6"
                                        class="field"
                                        name="auth6"
                                        placeholder="0"
                                        maxlength="1"
                                        title="Number only"
                                        required
                                    />
                                </div>
                            </div>
                            <p class="change">
                                Do you want to change your email?
                                <a href="" data-bs-toggle='modal' data-bs-target='#myChange'> Change</a>
                            </p>
                            <button id="btnVerify" class="field" name="btnVerify">
                                Submit
                            </button>
                        </section>
                    </form>
                    <form method="post">
                        <div class="resendForm">
                            Didn’t receive a code?
                            <button name="btnResend">Resend</button>
                        </div>
                    </form>
                </section>
            </div>
        </section>
    </main>

    <?php echo verify_modal(); ?>
    <script>
        function setFocus(currentInput, nextInputId) {
            if (currentInput.value.length === currentInput.maxLength) {
                document.getElementById(nextInputId).focus();
            }
        }

        getCurrentDate('date');
        function getCurrentDate(classname) {
            const dateOptions = {
                weekday: "long",
                day: "numeric",
                month: "short",
                year: "numeric",
                hour: "numeric",
                minute: "2-digit",
                hour12: true,
            };

            const currentDate = new Date().toLocaleString("en-PH", dateOptions);
            const elements = document.getElementsByClassName(classname);
            for (let i = 0; i < elements.length; i++) {
                elements[i].textContent = currentDate;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
