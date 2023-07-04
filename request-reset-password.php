<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/bootstrap.min.css">
		<style type="text/css">
			body {
				background-color: #202020;
				color: #dfdfdf;
			}
            input[type=text], input[type=email], input[type=password] {
                color: #dfdfdf;
                background-color: #3c4c55;
            }
			.input-group-text {
				background-color: #2c383e;
			}
		</style>

    <title>Reset Password</title>
</head>

<?php
session_start();
require_once('config.php');

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
?>

<body>
    <div class="container">
        <form action="request-reset-password.php" method="post">
            <div class="row justify-content-md-center">
                <div class="col-sm-4 border border-secondary border-3 rounded-3 p-5 m-5">
                    <h1>Reset Password</h1>

                    <?php
                    
                    if (isset($_POST["email"])) {
                        //Create an instance; passing `true` enables exceptions
                        $mail = new PHPMailer(true);

                        $email = $_POST["email"];
                        $token = uniqid();
                        $sql = "INSERT INTO reset(token, email) VALUES ('$token', '$email')";
                    
                        try {
                            //Server settings
                            $mail->isSMTP();                                            //Send using SMTP
                            $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
                            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                            $mail->Username   = 'secureprog0196@gmail.com';                     //SMTP username
                            $mail->Password   = 'pzkkfpsywroiztbd';                               //SMTP password
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;                                  //Enable implicit TLS encryption
                            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                    
                            //Recipients
                            $mail->setFrom('muhammadhaziq749@gmail.com', 'Haziq');
                            $mail->addAddress($email);     //Add a recipient
                            $mail->addReplyTo('no-reply@example.com', 'Information');
                    
                            //Content
                            $url = "localhost/sec_prog/reset-password.php?token=$token";
                            $mail->isHTML(true);                                  //Set email format to HTML
                            $mail->Subject = 'Reset your passwrod for Secure Programming';
                            $mail->Body    = "Click <a href='$url'>here</a> to reset your password";
                            $mail->AltBody = 'email body in plain text';
                    
                            $mail->send();
                            echo '<hr/>';

                            if(mysqli_query($conn, $sql)) {
                                $success = true;
                                ?>
                                <div class="alert alert-success" role="alert">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                                  </svg>
                                  Password reset link has been sent. Please check your email.
                                </div>
                                <?php
                              } else {
                                ?>
                                <div class="alert alert-danger" role="alert">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                  </svg>
                                  An error has occured: <?php echo mysqli_error($conn); ?>. Please try again!
                                </div>
                                <?php
                              }
                        } catch (Exception $e) {
                            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                        }
                    } else {

                    ?>

                    <hr/>
                    <p>A password reset link will be sent to your email.</p>
                    <div class="input-group mb-4">
                        <span class="input-group-text">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#dfdfdf" class="bi bi-person" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                            </svg>
                        </span>
                        <input class="form-control" type="text" name="email" placeholder="Enter your email">
				            </div>
                    <input class="btn btn-primary mb-3" type="submit" name="reset" value="Send email">

                    <?php } ?>

                </div>
            </div>
        </form>
    </div>
</body>
</html>