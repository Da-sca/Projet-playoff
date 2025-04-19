<?php
// Include the file where database connection is established
include 'functions.php';

// Create database connection
$conn = connectDatabase();

// Check if connection is successful
if (!$conn) {
    die("<p style='color:red;'>Échec de la connexion à la base de données: " . mysqli_connect_error() . "</p>");
} else {
    echo "<p style='color:green;'>Connexion réussie à la base de données.</p>";
}

// Fetch matches
$result = getMatches_user($conn);

// Check if query executed successfully
if (!$result) {
    die("<p style='color:red;'>Erreur SQL: " . mysqli_error($conn) . "</p>");
}

// Check if any rows were returned
$numRows = mysqli_num_rows($result);
echo "<p style='color:blue;'>Nombre de matchs trouvés: " . $numRows . "</p>";

if ($numRows > 0) {
    renderMatchTable_user($conn, $result);
} else {
    echo "<p style='color:red;'>Aucun match trouvé dans la base de données.</p>";
}

// Close the database connection
mysqli_close($conn);
?>
