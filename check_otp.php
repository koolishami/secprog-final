<?php
require_once('config.php');
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

session_start(['cookie_httponly' => true]);

// Redirect to login page if no user in session
if (!isset($_SESSION['login'])) {
    header('Location:index.php');
}

// Session hijacking mitigation
if ($_SESSION['user_agent'] != $_SERVER['HTTP_USER_AGENT']) {
    echo ('<script>alert("Invalid session detected"); location="logout.php"</script>');
}

$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html>

<head>
    <title>OTP Verification</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style type="text/css">
        body {
            background-color: #202020;
            color: #dfdfdf;
        }

        input[type=text],
        input[type=number],
        input[type=email],
        input[type=password] {
            color: #dfdfdf;
            background-color: #3c4c55;
        }

        .input-group-text {
            background-color: #2c383e;
        }
    </style>
</head>

<body>
    <div class="container">
        <form method="post">
            <div class="row justify-content-md-center">
                <div class="col-sm-4 border border-secondary border-3 rounded-3 p-5 m-5">
                    <h1>OTP Verification</h1>

                    <?php

                    use PHPMailer\PHPMailer\PHPMailer;
                    use PHPMailer\PHPMailer\SMTP;
                    use PHPMailer\PHPMailer\Exception;

                    if (isset($_POST['otp-button'])) {
                        echo 'post button is set';
                        $otp_recv = $_POST['otp'];

                        $otp_sql = "SELECT * FROM otp_verification WHERE email = '$email' AND valid_until = 0 AND otp = '$otp_recv'";
                        $result = $conn->query($otp_sql);

                        if ($result->num_rows > 0) {
                            $otp_date = "SYSDATE()";

                            if (isset($_POST['otp-checkbox'])) {
                                $otp_date = "DATE_ADD(sysdate(), INTERVAL 7 DAY)";
                            }

                            $sql = "UPDATE otp_verification SET valid_until = $otp_date WHERE email='$email' AND valid_until = 0;";

                            if(mysqli_query($conn, $sql)) {
                                header('Location:main.php');
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
                            
                        } else {
                            echo 'result is 0';
                    ?>

                            <div class="alert alert-danger" role="alert">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                </svg>
                                OTP Code verification failed. Please try again.
                            </div>

                        <?php
                        }
                    }

                    $otp_sql = "SELECT * FROM otp_verification WHERE email = '$email' AND SYSDATE() < valid_until";
                    $result = $conn->query($otp_sql);

                    if ($result->num_rows > 0) {
                        header('Location:main.php');
                    } else {
                        $otp_sql = "SELECT * FROM otp_verification WHERE email = '$email' AND valid_until = 0";
                        $result = $conn->query($otp_sql);

                        if ($result->num_rows > 0) {
                        ?>

                            <hr />
                            <p>We've sent an OTP code to your email. Enter the OTP code below to continue.</p>
                            <div class="input-group mb-4">
                                <span class="input-group-text">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#dfdfdf" class="bi bi-person" viewBox="0 0 16 16">
                                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                                    </svg>
                                </span>
                                <input class="form-control" type="text" name="otp" placeholder="OTP Code">
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" name="otp-checkbox" checked>
                                <label class="form-check-label" for="flexCheckChecked">
                                    Disable OTP for the next 7 days
                                </label>
                            </div>
                            <input class="btn btn-primary mb-3" type="submit" name="otp-button" value="Continue">

                    <?php
                        } else {
                            // send otp
                            $mail = new PHPMailer(true);

                            $otp = rand(100000, 999999);
                            $sql = "INSERT INTO otp_verification(email, otp) VALUES (?, ?)";

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
                                $mail->isHTML(true);                                  //Set email format to HTML
                                $mail->Subject = 'OTP for Pizza';
                                $mail->Body    = "OTP Code for your login is <b>'$otp'</b>";
                                $mail->AltBody = 'email body in plain text';

                                $mail->send();
                            } catch (Exception $e) {
                                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                            }

                            $stmt = mysqli_prepare($conn, $sql);
                            mysqli_stmt_bind_param($stmt, "ss", $email, $otp);

                            if (mysqli_stmt_execute($stmt)) {
                                header("Refresh:0");
                            } else {
                                display_error('An error has occurred: ' . mysqli_error($conn) . '. Please try again!');
                            }

                            mysqli_stmt_close($stmt);
                        }
                    }

                    ?>

                </div>
            </div>
        </form>
    </div>
</body>

</html>
<?php mysqli_close($conn); ?>