<?php 
require "query.php";

//security_index.php
if (isset($_POST['btnSignin'])) {
     sigin();
}


//security_forget.php
if (isset($_POST['btnForget'])) {
     forget();
}


//security_verify.php
if (isset($_POST['btnVerify'])) {
    verify();
}
else if (isset($_POST['btnResend'])) {
   sendemail($_SESSION['U_email'], "Email Verification", 1);
}
else if (isset($_POST['btnChange'])) {
   changeemail();	
}

?>