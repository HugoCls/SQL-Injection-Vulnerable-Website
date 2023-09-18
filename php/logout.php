<?php
session_start();
if (isset($_SESSION['username'])) {
    // Supprimez la variable de session de l'utilisateur
    unset($_SESSION['username']);
}

// Supprimez le cookie côté client en le mettant à une date d'expiration passée
setcookie('AUTH', '', time() - 3600, '/');

// Redirigez l'utilisateur vers la page de connexion ou toute autre page souhaitée après la déconnexion
header("Location: ../principale.html");
exit;
?>
