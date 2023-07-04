<?php
require_once('config.php');
session_start(['cookie_httponly' => true]);

// Redirect to login page if no user in session
if (!isset($_SESSION['login'])) {
	header('Location:index.php');
}

// Session hijacking mitigation
if ($_SESSION['user_agent'] != $_SERVER['HTTP_USER_AGENT']) {
	echo ('<script>alert("Invalid session detected"); location="logout.php"</script>');
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Main Page</title>
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
	<div class="container-fluid p-3 border-bottom border-dark border-3">
		<div class="row">
			<div class="col-md-1">
				<a href="history.php">
					<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#dfdfdf" class="bi bi-list" viewBox="0 0 16 16">
						<path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z" />
					</svg>
				</a>
			</div>
			<div class="col-md-5">
				<h2>Pizza Ordering Online</h2>
			</div>
			<div class="col-md-1 offset-md-5">
				<a class="btn btn-danger" href="logout.php">Log out</a>
			</div>
		</div>
	</div>

	<div class="offset-md-1 p-3">
		<br />
		<h4>Hello, <?php echo $_SESSION['email']; ?>, select your items.</h4>
		<br />

		<form action="confirm_order.php" method="post">
			<div class="col">
				<div class="row">
					<div class="col-2 p-2">
						<img src="img/slice.jpg" class="img-fluid" style="max-height:100px; width:100%; object-fit: cover;">
					</div>
					<div class="col-2 p-2">
						<p>Pepperoni Slice</p>
						<p>Price: RM5</p>
						Quantity:
						<input type="number" name="input1" value="0" min="0" max="20" style="width:35px;">
					</div>
				</div>
				<div class="row">
					<div class="col-2 p-2">
						<img src="img/margherita.jpg" class="img-fluid" style="max-height:100px; width:100%; object-fit: cover;">
					</div>
					<div class="col-2 p-2">
						<p>Margherita</p>
						<p>Price: RM15</p>
						Quantity:
						<input type="number" name="input2" value="0" min="0" max="20" style="width:35px;">
					</div>
				</div>
				<div class="row">
					<div class="col-2 p-2">
						<img src="img/egg.jpg" class="img-fluid" style="max-height:100px; width:100%; object-fit: cover;">
					</div>
					<div class="col-2 p-2">
						<p>Egg Pizza</p>
						<p>Price: RM12</p>
						Quantity:
						<input type="number" name="input3" value="0" min="0" max="20" style="width:35px;">
					</div>
				</div>
				<div class="row">
					<div class="col-2 p-2">
						<img src="img/small.jpg" class="img-fluid" style="max-height:100px; width:100%; object-fit: cover;">
					</div>
					<div class="col-2 p-2">
						<p>Small Pizza</p>
						<p>Price: RM3</p>
						Quantity:
						<input type="number" name="input4" value="0" min="0" max="20" style="width:35px;">
					</div>
				</div>
			</div>
			<input type="submit" name="order" value="Proceed">
		</form>
	</div>
</body>

</html>
<?php mysqli_close($conn); ?>