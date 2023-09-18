<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Paramètres de connexion à la base de données
    $db_username = 'root';
    $db_password = 'colson';
    $db_name = 'connexions';
    $db_host = 'localhost';

    // Se connecter à la base de données
    $db = mysqli_connect($db_host, $db_username, $db_password, $db_name)
        or die('Could not connect to the database');

    // Récupérer les données du formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Préparer la requête SQL pour vérifier l'utilisateur
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) == 1) {
        // L'utilisateur est authentifié avec succès, générons un jeton d'authentification
        $token = bin2hex(random_bytes(32)); // Crée un jeton aléatoire
        $expiration = date("Y-m-d H:i:s", strtotime("+1 hour")); // Expire dans 1 heure (vous pouvez modifier cela)

        // Récupérer l'ID de l'utilisateur
        $user_row = mysqli_fetch_assoc($result);
        $user_id = $user_row['id'];

        // Enregistrez le jeton dans la table user_auth_tokens
        $insert_token_query = "INSERT INTO user_auth_tokens (user_id, auth_token, expiration) VALUES ('$user_id', '$token', '$expiration')";
        mysqli_query($db, $insert_token_query);

        // Stocker le jeton dans un cookie sécurisé (https only)
        setcookie('AUTH', $token, strtotime($expiration), '/', '', true, false);

        // Rediriger vers la page principale
        header("Location: ../principale.html");
    } else {
        // Échec de l'authentification
        header("Location: ../login.html?erreur=1");
    }

    // Fermer la connexion à la base de données
    mysqli_close($db);
}
?>
