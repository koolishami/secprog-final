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
        echo ('<script>alert("Invalid session"); location="main.php"</script>');
    }

    if (!isset($_POST['confirm']) || !isset($_POST['cardNumber']) || !isset($_POST['month']) || !isset($_POST['year']) || !isset($_POST['cvv'])) {
        echo ('<script>alert("We encountered some error, redirecting to main page"); location="main.php"</script>');
    } else {
        function aes_encrypt($string)
        {
            $encrypt_method = "AES-256-CBC";
            $private_key = "secret_password";

            $encrypted = @openssl_encrypt($string, $encrypt_method, $private_key);

            return $encrypted;
        }

        $stmt = $conn->prepare("INSERT INTO payment (email, total_amount, card_num, expiry_month, expiry_year, cvv) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissss", $email, $amount, $encrypted_card, $month, $year, $cvv);

        $email = $_SESSION['email'];
        $amount = $_SESSION['total'];
        $encrypted_card = aes_encrypt($_POST['cardNumber']);
        $month = aes_encrypt($_POST['month']);
        $year = aes_encrypt($_POST['year']);
        $cvv = aes_encrypt($_POST['cvv']);
        $stmt->execute();

        $stmt->close();
    }
}
?>

<!DOCTYPE html>

<head>
    <title>Payment Status</title>
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
                    <h1>Payment Status</h1>
                    <hr />
                    <div class="alert alert-success" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                            <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z" />
                        </svg>
                        Your payment has been accepted. Thank you for your order!
                    </div>
                    <br />
                    <a class="btn btn-primary" href="main.php">Back to Main Menu</a>
                    <p></p>
                </div>
            </div>
        </form>
    </div>
</body>

</html>