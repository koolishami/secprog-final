<?php
require_once('config.php');
session_start(['cookie_httponly' => true]);

// Redirect to login page if no user in session
if(!isset($_SESSION['login'])) {
	header('Location:index.php');
} else {
    if ($_SESSION['user_agent'] != $_SERVER['HTTP_USER_AGENT']) {
        echo('<script>alert("Invalid session detected"); location="logout.php"</script>');
    }

    if (!isset($_POST['order'])) {
        header('Location: main.php');
    } else {
        $input = array();
        for ($l=1; $l<=4; $l++) {
            array_push($input, htmlspecialchars($_POST['input' . $l]));
        }

        $cartEmpty = true;
        for($i=0; $i<4; $i++) {
            if ($input[$i] != 0) {
                $cartEmpty = false;
                break;
            }
        }

        if ($cartEmpty) {
            echo('<script>alert("Cart is empty"); location="main.php"</script>');
        } else {
            $total = 0;
            $total += $input[0]*5;
            $total += $input[1]*15;
            $total += $input[2]*12;
            $total += $input[3]*3;

            // Mitigate Cross Site Request Forgery
            $_SESSION['csrf_token'] = bin2hex(random_bytes(35));

            // Mitigate session fixation
            session_regenerate_id();
        }
    }
}
?>

<!DOCTYPE html>
    <head>
		<title>Order Confirmation</title>
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
	</head>
    <body>
        <div class="container">
			<form class="" action="payment_page.php" method="post">
				<div class="row justify-content-md-center">
					<div class="col-sm-4 border border-secondary border-3 rounded-3 p-5 m-5 pb-1">
						<h1>Order List</h1>
						<hr/>
                        <div class="col">

                            <?php
                                for ($j=0; $j<4; $j++) {
                                    if ($input[$j] != 0) {
                                        ?>
                                        <div class="row justify-content-between">
                                            <div class="col-8">
                                                <?php 
                                                    echo
                                                        ($j==0 ? 'Pepperoni Slice' :
                                                        ($j==1 ? 'Pizza Margherita' :
                                                        ($j==2 ? 'Egg Pizza' :
                                                        ($j==3 ? 'Small Pizza' : ''))));

                                                    echo ' x ' . $input[$j];
                                                ?>
                                            </div>
                                            <div class="col-4">
                                                <?php 
                                                    echo 'RM ';
                                                    echo
                                                        ($j==0 ? 5*$input[$j] :
                                                        ($j==1 ? 15*$input[$j] :
                                                        ($j==2 ? 12*$input[$j] :
                                                        ($j==3 ? 3*$input[$j] : ''))));
                                                ?>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                            ?>
                            <hr>
                            <div class="row justify-content-between">
                                <div class="col-8">
                                    <h4>Grand Total</h4>
                                </div>
                                <div class="col-4">
                                    <h4>RM <?php echo $total ?></h4>
                                </div>
                            </div>
                            <br>
                            <div class="row justify-content-between">
                                <div class="col-3">
                                    <input class="btn btn-danger mb-3" type="button" name="cancel" value="Cancel" onclick="location.href = 'main.php';">
                                </div>
                                <div class="col-3">
                                    <input type="hidden" name="total" value="<?php echo $total ?>">
                                    <!-- Mitigate Cross Site Request Forgery -->
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <input class="btn btn-primary mb-3" type="submit" name="confirm" value="Confirm">
                                </div>
                            </div>
                        </div>
						<br/>					
					</div>
				</div>
			</form>
		</div>
    </body>
</html>