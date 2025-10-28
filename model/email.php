<?php 
 use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\SMTP;
 use PHPMailer\PHPMailer\Exception;

 require 'PHPMailer/src/Exception.php';
 require 'PHPMailer/src/PHPMailer.php';
 require 'PHPMailer/src/SMTP.php';

function generateRandomString() {
	$number = '0123456789';
    $lcharacters = 'abcdefghijklmnopqrstuvwxyz';
	$ucharacters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$special = '#$%&*?';
    return substr(str_shuffle($number), 0, 3) . substr(str_shuffle($lcharacters), 0, 2) . substr(str_shuffle($ucharacters), 0, 2). substr(str_shuffle($special ), 0, 1);
}

function sendemail($email, $subject, $message) {
    
    if($message == 1){
       $_SESSION['otp_code'] = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
       $message =  $_SESSION['otp_code'];
    }
    elseif ($message == 2){
       $_SESSION['U_password'] = generateRandomString();
       $message =  $_SESSION['U_password'];
    }
    
    $mail = new PHPMailer(true);
    try {
       
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'enzocunanan01@gmail.com';
        $mail->Password = 'tmku egud eucf wtxz';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('enzocunanan01@gmail.com', 'Baliwag South Central School');
        $mail->addAddress($email, $email);
        $mail->isHTML(true);

        $mail->Subject = $subject;

        $mail->Body = "<div
                            style='
                                font-family: Arial, sans-serif;
                                color: #333;
                                background-color: #ffffff;
                                padding: 30px;
                                border-radius: 10px;
                                max-width: 600px;
                                margin: 0 auto;
                                box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
                            '
                        >
                            <div
                                style='
                                    display: flex;
                                    width: 100%;
                                    height: 100px;
                                    justify-content: center;
                                    margin: 0 auto;
                                    margin-bottom: 70px;
                                    
                                '
                            >
                                <img src='https://tinypic.host/images/2024/10/31/BSCS-logo-1.png' alt='' style='object-fit: cover; margin: 0 0 0 auto;' />
                                <div style='margin: 0 auto 0 0'>
                                    <h1 style='color: black; margin: 0;'>INFORMATION</h1>
                                    <h3 style='color: black; margin: 0;'>SYSTEM</h3>
                                </div>
                            </div>

                            <p style='text-align: center; margin-bottom: 70px'>
                                <b
                                    style='
                                        font-size: 50px;
                                        color: #42b4ee;
                                        border-bottom: 1px dotted #ffffff;
                                    '
                                    >$message</b
                                >
                            </p>

                            <h4 style='color: black; margin: 0'>Thank you, $email!</h4>
                            <p style='font-size: 12px; color: #999'>
                                If you did not request this, please ignore this email.
                            </p>
                        </div>";
        
       
        $mail->send();
        echo alert("<script>showalert('success', '<strong>Alert</strong> <br/> <br/> Successfully sent! Please check your email.');</script>");
    } catch (Exception $e) {
        echo alert("<script>showalert('danger', '<strong>Alert</strong> <br/> <br/> Message could not be sent. Mailer Error: {$mail->ErrorInfo}');</script>");
    }
}

function sendEventEmail($email, $eventType, $title, $description, $date, $time, $eventScope) {
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'enzocunanan01@gmail.com';
        $mail->Password = 'tmku egud eucf wtxz';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('enzocunanan01@gmail.com', 'Baliwag South Central School');
        $mail->addAddress($email);
        $mail->isHTML(true);

        $subject = ($eventType == 'upcoming') ? "Upcoming Event: $title" : "Event Update: $title";

        $borderColor = ($eventScope == 'School-Wide') ? 'red' : 'green';
        $bgColor = ($eventScope == 'School-Wide') ? '#ffd6d6' : '#d6ffd6';

        $mail->Subject = $subject;
        $mail->Body = "
            <div style='
                font-family: Arial, sans-serif;
                color: #333;
                background-color: #f9f9f9;
                padding: 20px;
                border-radius: 10px;
                max-width: 600px;
                margin: 0 auto;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                border-left: 5px solid $borderColor;
            '>
                <div style='text-align: center; margin-bottom: 40px;'>
                    <img src='https://tinypic.host/images/2024/10/31/BSCS-logo-1.png' alt='Information System Logo' style='width: 80px; height: auto; margin-bottom: 10px;' />
                    <h1 style='color: #333; font-size: 15px; margin:0;'>INFORMATION SYSTEM</h1>
                </div>
                <div style='background-color: $borderColor; padding: 5px 10px; border-radius: 20px; display: inline-block;'>
                    <h5 style='color: white; text-align: center; margin: 0; padding: 5px 10px;'>$eventScope</h5>
                </div>
                <div style='margin: 20px 0;'>
                    <strong>Title:</strong>
                    <p> $title</p>

                    <strong>Description:</strong>
                    <p>$description</p>

                    <div style='display:flex;'>
                        <div>
                            <p><strong>Date:</strong> $date</p>
                        </div>  
                        <div style = 'margin-left:10px;'>
                            <p><strong>Time:</strong> $time</p>
                        </div>
                    </div>
                </div>
                <hr style='border: none; border-top: 1px solid #ddd;'>
                <p style='font-size: 12px; color: #999; text-align: center;'>
                    If you have questions, please contact us at <a href='mailto:enzocunanan01@gmail.com' style='color: #42b4ee;'>enzocunanan01@gmail.com</a>.
                </p>
            </div>";

        $mail->send();
        echo "<script>showalert('success', 'Event email successfully sent!');</script>";
    } catch (Exception $e) {
        echo "<script>showalert('danger', 'Message could not be sent. Mailer Error: {$mail->ErrorInfo}');</script>";
    }
}


?>