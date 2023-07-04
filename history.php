<?php
require_once('config.php');
session_start(['cookie_httponly' => true]);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function aes_decrypt($string)
{
    $encrypt_method = "AES-256-CBC";
    $private_key = "secret_password";

    $decrypted = openssl_decrypt($string, $encrypt_method, $private_key);

    return $decrypted;
}

$email = $_SESSION['email'];
$sql = "SELECT * FROM payment WHERE email = '$email';";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $card = aes_decrypt($row['card_num']);
        $month = aes_decrypt($row['expiry_month']);
        $year = aes_decrypt($row['expiry_year']);
        $cvv = aes_decrypt($row['cvv']);

        echo $card . " - " . $month . " - " . $year . " - " . $cvv;
    }
} else {
    echo "0 results";
}
