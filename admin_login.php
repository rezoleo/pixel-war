<?php

include('./private/config.php');

if (isset($_POST['passwd'])) {
    $enteredPassword = $_POST['passwd'];

    if (password_verify($enteredPassword, $hashedPassword)) {
        // Mot de passe correct, rediriger vers admin_control.html
        session_start();
        $_SESSION['authenticated'] = true;
        $_SESSION['user_type'] = 'admin';
        header("Location: admin_control.php");
        exit();
    } else {
        // Mot de passe incorrect, rediriger vers admin_login.html avec un message d'erreur
        header("Location: admin_login.html?error=true");
        exit();
    }
}
?>