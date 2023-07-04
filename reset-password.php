<?php
require 'config.php';
?>

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
<body>
    <div class="container">
        <form method="post">
            <div class="row justify-content-md-center">
                <div class="col-sm-4 border border-secondary border-3 rounded-3 p-5 m-5">
                    <h1>Reset Password</h1>
                    <hr/>

                    <?php
                        if(!isset($_GET['token'])) {
                            echo("<hr/>Token invalid or expired, please make sure to open this page from your email.");
                        } else {
                            $token = $_GET['token'];
                
                            $sql = "SELECT * FROM reset WHERE token = '$token'";
                            $result = mysqli_query($conn, $sql);
                
                            if(mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_assoc($result);
                    ?>

                    <div class="input-group mb-4">
                        <span class="input-group-text">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#dfdfdf" class="bi bi-key" viewBox="0 0 16 16">
                                <path d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5z"/>
                                <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                            </svg>
                        </span>
                        <input class="form-control" type="text" name="password" placeholder="New password">
                        <input type="hidden" name="email" value="<?php echo($row['email']); ?>">
                    </div>
                    <input class="btn btn-primary mb-3" type="submit" name="reset" value="Reset password">

                    <?php
                            } else {
                                echo("<hr/>Token invalid or expired, please make sure to open this page from your email.");
                            }
                        }

                        if(isset($_POST['password'])) {
                            $email = $_POST['email'];
                            $password = mysqli_real_escape_string($conn, $_POST['password']);

                            $salt = bin2hex(random_bytes(5));
                            $salted = $password . $salt;
                            $hash = hash("sha256", $salted);

                            $sql = "UPDATE users SET hashed_pw = '$hash', salt = '$salt' WHERE username='$email';";

                            if(mysqli_query($conn, $sql)) {
                                mysqli_query($conn, "DELETE FROM reset WHERE token = '$token'");
                                ?>
                                <div class="alert alert-success" role="alert">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                                  </svg>
                                  Password changed successfully. You can login <a href="index.php" class="alert-link">here</a>.
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
                        }
                    ?>
                </div>
            </div>
        </form>
    </div>
</body>
</html>