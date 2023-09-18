<?php
// Connexion à la base de données (remplacez les valeurs par les vôtres)
$db_username = 'root';
$db_password = 'colson';
$db_name = 'connexions';
$db_host = 'localhost';
$db = mysqli_connect($db_host, $db_username, $db_password,$db_name)
or die('could not connect to database');

// Récupérer les données du formulaire
$email = $_POST['email'];
$password = $_POST['password'];

// Préparer la requête SQL pour l'insertion
$requete = "INSERT INTO users (username, password) VALUES (?, ?)";
$stmt = mysqli_prepare($db, $requete);
mysqli_stmt_bind_param($stmt, "ss", $email, $password);


header("Location: ../principale.html"); // Rediriger vers la page de connexion après l'inscription réussie

mysqli_stmt_close($stmt);
mysqli_close($db); // Fermer la connexion
?>