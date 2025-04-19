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

// Ajouter une équipe
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_team'])) {
    $nom_equipe = mysqli_real_escape_string($conn, $_POST['nom_equipe']);
    $nombre_de_victoire = intval($_POST['nombre_de_victoire']);
    $nombre_de_defaite = intval($_POST['nombre_de_defaite']);

    $query = "INSERT INTO equipe (nom_equipe, nombre_de_victoire, nombre_de_defaite) 
              VALUES ('$nom_equipe', $nombre_de_victoire, $nombre_de_defaite)";

    if (!mysqli_query($conn, $query)) {
        echo "<p style='color:red;'>Erreur lors de l'ajout de l'équipe: " . mysqli_error($conn) . "</p>";
    }
}

// Modifier une équipe
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_team'])) {
    $id_equipe = intval($_POST['id_equipe']);
    $nom_equipe = mysqli_real_escape_string($conn, $_POST['nom_equipe']);
    $nombre_de_victoire = intval($_POST['nombre_de_victoire']);
    $nombre_de_defaite = intval($_POST['nombre_de_defaite']);

    $query = "UPDATE equipe 
              SET nom_equipe='$nom_equipe', 
                  nombre_de_victoire=$nombre_de_victoire, 
                  nombre_de_defaite=$nombre_de_defaite 
              WHERE id_equipe=$id_equipe";

    if (!mysqli_query($conn, $query)) {
        echo "<p style='color:red;'>Erreur lors de la modification de l'équipe: " . mysqli_error($conn) . "</p>";
    }
}

// Supprimer une équipe
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_team'])) {
    $id_equipe = intval($_POST['id_equipe']);

    $delete_players = "DELETE FROM joueurs WHERE id_equipe=$id_equipe";
    mysqli_query($conn, $delete_players);

    $query = "DELETE FROM equipe WHERE id_equipe=$id_equipe";

    if (!mysqli_query($conn, $query)) {
        echo "<p style='color:red;'>Erreur lors de la suppression de l'équipe: " . mysqli_error($conn) . "</p>";
    }
}

$query = "SELECT * FROM equipe";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Équipes</title>
    <link rel="stylesheet" href="Page_Modification.css">
</head>
<body>
    <header>
        <nav>
            <ul>
            <li><a href="acceuil.php" class="active">Acceuil</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Gérer les Équipes</h2>
        
        <h3>Ajouter une Équipe</h3>
        <form method="POST" action="">
            <label>Nom de l'équipe : <input type="text" name="nom_equipe" required></label>
            <label>Nombre de victoires : <input type="number" name="nombre_de_victoire" value="0" required></label>
            <label>Nombre de défaites : <input type="number" name="nombre_de_defaite" value="0" required></label>
            <button type="submit" name="add_team">Ajouter</button>
        </form>
        
        <h3>Liste des Équipes</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Victoires</th>
                <th>Défaites</th>
                <th>Actions</th>
                <th>Modifier Joueurs</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <form method="POST" action="">
                    <td><?php echo $row['id_equipe']; ?></td>
                    <td><input type="text" name="nom_equipe" value="<?php echo htmlspecialchars($row['nom_equipe']); ?>" required></td>
                    <td><input type="number" name="nombre_de_victoire" value="<?php echo $row['nombre_de_victoire']; ?>" required></td>
                    <td><input type="number" name="nombre_de_defaite" value="<?php echo $row['nombre_de_defaite']; ?>" required></td>
                    <td>
                        <input type="hidden" name="id_equipe" value="<?php echo $row['id_equipe']; ?>">
                        <button type="submit" name="update_team">Modifier</button>
                        <button type="submit" name="delete_team" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette équipe ?');">Supprimer</button>
                    </td>
                    <td><a href="modifier_joueurs.php?id_equipe=<?php echo $row['id_equipe']; ?>">Modifier Joueurs</a></td>
                </form>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
