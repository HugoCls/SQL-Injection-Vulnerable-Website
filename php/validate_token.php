<?php
session_start();
$db_username = 'root';
$db_password = 'colson';
$db_name = 'connexions';
$db_host = 'localhost';
$db = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($db->connect_error) {
    die("La connexion à la base de données a échoué : " . $db->connect_error);
}

$request_data = json_decode(file_get_contents("php://input"), true);
$AUTH = $request_data['AUTH'];

// Vérifiez si le jeton d'authentification existe et n'a pas expiré
$checkQuery = "SELECT COUNT(*) AS valid FROM user_auth_tokens WHERE auth_token = ? AND expiration >= NOW()";
$stmt = $db->prepare($checkQuery);
$stmt->bind_param("s", $AUTH);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$isTokenValid = $row['valid'] == 1;

echo json_encode(['valid' => $isTokenValid]);

$stmt->close();
$db->close();
?>