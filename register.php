<?php
require_once('config.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Registration Page</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style type="text/css">
			body {
				background-color: #202020;
				color: #dfdfdf;
			}
      input[type=text], input[type=password] {
        color: #dfdfdf;
        background-color: #3c4c55;
      }
			.input-group-text {
				background-color: #2c383e;
			}
		</style>
  </head>
  <body>
    <?php $success = false; ?>
    <form action="register.php" method="post">
      <div class="container-fluid">
        <div class="row justify-content-lg-center">
          <div class="col-sm-4 border border-secondary border-3 rounded-3 p-5 m-5">
            <h1>Registration Form</h1>
            <hr/>

            <div class="form-label">Email: </div>
            <input class="form-control" type="text" name="username" placeholder="e.g. mail@example.com" required>
            <br/>

            <div class="form-label">Password: </div>
            <input class="form-control" type="password" name="password" placeholder="Enter password" required>
            <br/>

            <?php
            function sanitize_input($sql_connection, $string) {
              $newstr = mysqli_real_escape_string($sql_connection, $string);
              $newstr = filter_var($newstr, FILTER_SANITIZE_STRING);

              return $newstr;
            }

            if(isset($_POST['submit'])) {
              $username = sanitize_input($conn, $_POST['username']);
              $password = sanitize_input($conn, $_POST['password']);

              $salt = bin2hex(random_bytes(5));
              $salted = $password . $salt;
              $hash = hash("sha256", $salted);

              $sql = "INSERT INTO users(username, hashed_pw, salt) VALUES ('$username', '$hash', '$salt')";

              if(mysqli_query($conn, $sql)) {
                $success = true;
                ?>
                <div class="alert alert-success" role="alert">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                  </svg>
                  You have succesfully registered! You can login with your credentials via the <a href="index.php" class="alert-link">login page</a>.
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

            <div class="row mx-5 mt-3">
              <input class="btn btn-outline-primary mb-3" type="submit" name="submit" value="Register"<?php if($success) echo "disabled"; ?>>
              <a class="btn btn-outline-danger" href="index.php">Back to Login Page</a>
            </div>
          </div>
        </div>
      </div>
    </form>
  </body>
</html>
