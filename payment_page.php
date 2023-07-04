<?php
require_once('config.php');
session_start(['cookie_httponly' => true]);

// Redirect to login page if no user in session
if (!isset($_SESSION['login'])) {
    header('Location:index.php');
} else {
    if ($_SESSION['user_agent'] != $_SERVER['HTTP_USER_AGENT']) {
        echo ('<script>alert("Invalid session detected"); location="logout.php"</script>');
    }

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] != $_SESSION['csrf_token']) {
        echo ('<script>alert("Invalid session"); location="logout.php"</script>');
    } else {
        $_SESSION['total'] = $_POST['total'];
    }
}
?>

<!DOCTYPE html>

<head>
    <title>Payment Page</title>
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
        <form action="check_payment.php" method="post">
            <div class="row justify-content-md-center">
                <div class="col-sm-4 border border-secondary border-3 rounded-3 p-5 m-5 pb-1">
                    <h1>Payment</h1>
                    <hr />
                    <div class="col">
                        <div class="row">
                            <p>Please complete your payment.</p>
                        </div>
                        <div class="row justify-content-between">
                            <div class="col-8">
                                <h4>Total Amount</h4>
                            </div>
                            <div class="col-4">
                                <h4>RM <?php echo $_POST['total']; ?></h4>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="form-group">
                                <label for="cardNumber">Card number</label>
                                <div class="input-group">
                                    <input type="text" name="cardNumber" placeholder="1234 1234 1234 1234" class="form-control" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label><span class="hidden-xs">Expiration</span></label>
                                        <div class="input-group">
                                            <input type="number" placeholder="MM" name="month" class="form-control" required>
                                            <input type="number" placeholder="YY" name="year" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group mb-4">
                                        <label data-toggle="tooltip" title="Three-digits code on the back of your card">CVV
                                            <i class="fas fa-question-circle"></i>
                                        </label>
                                        <input type="text" required name="cvv" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row justify-content-between">
                            <div class="col-3">
                                <input class="btn btn-danger mb-3" type="button" name="cancel" value="Cancel" onclick="location.href = 'main.php';">
                            </div>
                            <div class="col-3">
                                <input type="hidden" name="csrf_token" value="<?php echo $_POST['csrf_token'] ?>">
                                <input class="btn btn-primary mb-3" type="submit" name="confirm" value="Confirm">
                            </div>
                        </div>
                    </div>
                    <br />
                </div>
            </div>
        </form>
    </div>
</body>

</html>