<?php

include('./private/config.php');

if (isset($_POST['passwd'])) {
    $enteredPassword = $_POST['passwd'];

    // Vérifier le mot de passe (vous devez utiliser une méthode de hachage sécurisée ici)
    if (password_verify($enteredPassword, $hashedPassword)) {
        // Mot de passe correct, rediriger vers admin_control.html
        header("Location: admin_control.html");
        exit();
    } else {
        // Mot de passe incorrect, rediriger vers admin_login.html avec un message d'erreur
        header("Location: admin_login.html?error=true");
        exit();
    }
}
?>
