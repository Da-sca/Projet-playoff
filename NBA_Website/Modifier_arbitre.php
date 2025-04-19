<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$user = "root";
$passwd = "";
$bdd = "playoff_try";

$conn = mysqli_connect($host, $user, $passwd, $bdd);

if (!$conn) {
    die("<p style='color:red;'>Erreur de connexion: " . mysqli_connect_error() . "</p>");
}

// Ajouter un arbitre
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_arbitre'])) {
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $age = intval($_POST['age']);
    $experience = intval($_POST['experience']);

    $query = "INSERT INTO arbitres (nom, age, experience)
              VALUES ('$nom', $age, $experience)";

    if (!mysqli_query($conn, $query)) {
        echo "<p style='color:red;'>Erreur lors de l'ajout de l'arbitre: " . mysqli_error($conn) . "</p>";
    }
}

// Modifier un arbitre
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_arbitre'])) {
    $id_arbitre = intval($_POST['id_arbitre']);
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $age = intval($_POST['age']);
    $experience = intval($_POST['experience']);

    $query = "UPDATE arbitres 
              SET nom='$nom', age=$age, experience=$experience 
              WHERE id_arbitre=$id_arbitre";

    if (!mysqli_query($conn, $query)) {
        echo "<p style='color:red;'>Erreur lors de la modification de l'arbitre: " . mysqli_error($conn) . "</p>";
    }
}

// Supprimer un arbitre
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_arbitre'])) {
    $id_arbitre = intval($_POST['id_arbitre']);

    $query = "DELETE FROM arbitres WHERE id_arbitre=$id_arbitre";

    if (!mysqli_query($conn, $query)) {
        echo "<p style='color:red;'>Erreur lors de la suppression de l'arbitre: " . mysqli_error($conn) . "</p>";
    }
}

// Récupérer les arbitres
$query = "SELECT * FROM arbitres";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Arbitres</title>
    <link rel="stylesheet" href="Modifier_arbitre.css">
</head>
<body>
    <header>
        <nav>
            <ul>
            <li><a href="acceuil.php" class="active">Accueil</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Gérer les Arbitres</h2>
        <h3>Ajouter un Arbitre</h3>
        <form method="POST" action="">
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="number" name="age" placeholder="Âge" required>
            <input type="number" name="experience" placeholder="Expérience" required>
            <button type="submit" name="add_arbitre">Ajouter</button>
        </form>
        <hr>
        <h3>Liste des Arbitres</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Âge</th>
                <th>Expérience</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <form method="POST">
                    <td><?php echo htmlspecialchars($row['id_arbitre']); ?></td>
                    <td><input type="text" name="nom" value="<?php echo htmlspecialchars($row['nom']); ?>" required></td>
                    <td><input type="number" name="age" value="<?php echo $row['age']; ?>" required></td>
                    <td><input type="number" name="experience" value="<?php echo $row['experience']; ?>" required></td>
                    <td>
                        <input type="hidden" name="id_arbitre" value="<?php echo $row['id_arbitre']; ?>">
                        <button type="submit" name="update_arbitre">Modifier</button>
                        <button type="submit" name="delete_arbitre" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet arbitre ?');">Supprimer</button>
                    </td>
                </form>
            </tr>
            <?php endwhile; ?>
        </table>    
    </div>

    <footer>
        <p>&copy; 2025 Gestion des Arbitres NBA</p>
    </footer>
</body>
</html>
<?php
// Fermer la connexion
mysqli_close($conn);
?>