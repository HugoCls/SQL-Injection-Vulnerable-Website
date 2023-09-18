<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "colson";
$dbname = "connexions";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

// Récupérer la valeur de recherche depuis le formulaire
$searchQuery = isset($_POST['search']) ? $_POST['search'] : '';

// Si la recherche est vide, afficher tous les livres
if ($searchQuery === '') {
    $sql = "SELECT name, author, front_page FROM books";
} else {
    // Requête SQL pour rechercher des livres en fonction de la valeur de recherche
    $sql = "SELECT name, author, front_page FROM books WHERE name LIKE '%$searchQuery%'";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $results = array();

    while ($row = $result->fetch_assoc()) {
        $results[] = array(
            'name' => $row['name'],
            'author' => $row['author'],
            'front_page' => $row['front_page']
        );
    }

    echo json_encode($results);
} else {
    echo json_encode(array('message' => 'Aucun livre trouvé dans la base de données.'));
}

$conn->close();
?>
