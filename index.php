<?php
require_once('config.php');
// Mitigate session hijacking
session_start(['cookie_httponly' => true]);

// If session shows user is already logged in, redirect to main page
if (isset($_SESSION['login'])) {
	if ($_SESSION['login']) {
		header('Location:check_otp.php');
	}
}

?>
<!DOCTYPE html>
<html>

<head>
	<title>Login Page</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<style type="text/css">
		body {
			background-color: #202020;
			color: #dfdfdf;
		}

		input[type=text],
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
	<?php
	if (isset($_POST['login'])) {
		$username = mysqli_real_escape_string($conn, $_POST['username']);
		$password = mysqli_real_escape_string($conn, $_POST['password']);

		$sql = "SELECT * FROM users WHERE username = '$username';";
		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) == 1) {
			$row = mysqli_fetch_assoc($result);

			// Get hashed password and salt for specified user from database
			$salt = $row['salt'];
			$hashedpw_db = $row['hashed_pw'];

			// Hashing user input password with hash from db
			$salted = $password . $salt;
			$hashedpw = hash("sha256", $salted);

			// Checking if hashed user input == hashed pw from db
			if ($hashedpw == $hashedpw_db) {
				$_SESSION['login'] = true;
				$_SESSION['email'] = $username;

				// Mitigate session fixation
				session_regenerate_id();

				// Mitigate session hijacking
				if (!isset($_SESSION['user_agent'])) {
					$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
				}

				header('Location:check_otp.php');
			} else {
				$_SESSION['login'] = false;
			}
		} else {
			$_SESSION['login'] = false;
		}
	} ?>
	<div class="container">
		<form class="" action="index.php" method="post">
			<div class="row justify-content-md-center">
				<div class="col-sm-4 border border-secondary border-3 rounded-3 p-5 m-5">
					<h1>Login</h1>
					<hr />
					<div class="input-group mb-4">
						<span class="input-group-text">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#dfdfdf" class="bi bi-person" viewBox="0 0 16 16">
								<path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
							</svg>
						</span>
						<input class="form-control" type="text" name="username" placeholder="Email">
					</div>

					<div class="input-group mb-4">
						<span class="input-group-text">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#dfdfdf" class="bi bi-key" viewBox="0 0 16 16">
								<path d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5z" />
								<path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
							</svg>
						</span>
						<input class="form-control" type="password" name="password" placeholder="Password">
					</div>
					<br>

					<?php

					// If session login is false, show err msg
					if (isset($_SESSION['login'])) {
						if ($_SESSION['login'] == false) {
							unset($_SESSION['login']);
					?>
							<div class="alert alert-danger" role="alert">
								<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
									<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
									<path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
								</svg>
								Login failed. Please check your username and password.
							</div>
					<?php
						}
					}
					?>

					<input class="btn btn-primary mb-3" type="submit" name="login" value="Login">
					<br />
					<a href="request-reset-password.php">Forgot your password?</a>
					<br />
					<a href="register.php">Sign Up</a>

				</div>
			</div>
		</form>
	</div>
</body>

</html>