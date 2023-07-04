<?php
session_start(['cookie_httponly' => true]);

// Mitigate session hijacking
session_unset();
session_destroy();

header('Location:index.php');
?>
