<?php 
require "../../../model/function.php";

//index.php function
function sigin(){
    global $data;

    $data['index_page']['check']['value'] = [
            $_POST['email'],
          ];

	list($checkSuccess, $checkResult) = select($data['index_page']['check']);
	if($checkSuccess){
         foreach($checkResult as $row){
			if(password_verify($_POST['password'], $row['us_password'])){

				if($row['us_verified'] == 1){
                    if($row['us_type'] === 'teacher'){
                       redirect("../teacher?teacher=".$row['us_id']);
					}elseif($row['us_type'] === 'registrar'){
                        redirect("../registrar?registrar=".$row['us_id']);

					 }elseif($row['us_type'] === 'principal' || $row['us_type'] == 'Secretary'){
                          redirect("../principal?principal=".$row['us_id']);
					   
					 }elseif($row['us_type'] === 'admin'){
                         redirect("../admin?admin=".$row['us_id']);
					 }
                    
				}else{
					$_SESSION['U_email'] = $row['us_email'];
					sendemail($_SESSION['U_email'], "Email Verification", 1);
					redirect("verify.php");
				}

			}else{
				echo alert("<script>showalert('danger', '<strong>Alert</strong> <br/> <br/> Please enter the correct password and try again.');</script>");
			}
		 }
		 
	}else{
		 echo alert("<script>showalert('danger', '<strong>Alert</strong> <br/> <br/> Please enter the correct email and try again.');</script>");
	}
}




//forget.php function
function forget(){
    global $data;
    $data['forgot_page']['check']['value'] = [
       $_POST['email'],
    ];

    list($checkSuccess, $checkResult) = select($data['forgot_page']['check']);

	if ($checkSuccess) { 
		sendemail($_POST['email'], "New Password", 2);

		$data['forgot_page']['forget']['value'] = [
					password_hash($_SESSION['U_password'], PASSWORD_DEFAULT),
					$_POST['email'],
				];

			if(insert($data['forgot_page']['forget'])){
				unset($_SESSION['U_Password']);
				echo alert("<script>showalert('success', '<strong>Alert</strong> <br/> <br/> Please check the \"New Password\" sent to your email.');</script>");
			}

	} else {
		echo alert("<script>showalert('danger', '<strong>Alert</strong> <br/> <br/> Your email does not exist in our record, pls used your registered email.');</script>");
	}
}

function forget_session() { 
    $_SESSION['U_password'] = ((isset($_SESSION['U_password'])) ? $_SESSION['U_password'] : '');
}




//verify.php
function verify(){
    global $data;

    $code = $_POST['auth1'].$_POST['auth2'].$_POST['auth3'].$_POST['auth4'].$_POST['auth5'].$_POST['auth6'];
 
	if($code == $_SESSION['otp_code']){
	    $data['verify_page']['verify']['value'] = [
			1,
			$_SESSION['U_email'],
		];
	    
		insert($data['verify_page']['verify']);
        unset($_SESSION['U_email']);
	    unset($_SESSION['otp_code']);
		redirect("index.php"); 

	}else{
		 echo alert("<script>showalert('warning', '<strong>Alert</strong> <br/> <br/> Please check the code sent to your email.');</script>");
	}   

}


function changeemail(){
    global $data;

    $data['verify_page']['check']['value'] = [
        $_POST['cemail'],
    ];

    list($checkSuccess, $checkResult) = select($data['verify_page']['check']);

	if (!$checkSuccess || empty($checkResult)) { 
		$data['verify_page']['email']['value'] = [
			$_POST['cemail'],
			$_SESSION['U_email'],
		];

		if(insert($data['verify_page']['email'])){
			$_SESSION['U_email'] = $_POST['cemail'];
            sendemail($_SESSION['U_email'], "Email Verification", 1);
		}
	} else {
		echo alert("<script>showalert('warning', '<strong>Alert</strong> <br/> <br/> Email already exists. Please use a different one.');</script>");
	}
}


function verify_modal($heading = "Email Heading", $modalId = "myChange", $buttonText = "Change") {
    return modal(
        $heading,
        "",
        "<div class='modal-body body1'>
            <section class='form'>
                <div class='groupfield'>
                    <label for='cemail'>New email</label> <br />
                    <input
                        type='email'
                        name='cemail'
                        class='form-control field'
                        id='cemail'
                        required
						placeholder = '@gmail'
                    />
                </div>
            </section>
        </div>",
        $modalId,
        $buttonText,
        0
    );
}


function verify_session() {
    $_SESSION['otp_code'] = ((isset($_SESSION['otp_code'])) ? $_SESSION['otp_code'] : '');
    $_SESSION['U_email'] = ((isset($_SESSION['U_email'])) ? $_SESSION['U_email'] : '');
}



?>