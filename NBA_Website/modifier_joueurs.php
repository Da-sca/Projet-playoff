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

// Vérifier si l'ID de l'équipe est fourni
if (!isset($_GET['id_equipe'])) {
    die("<p style='color:red;'>Aucune équipe spécifiée.</p>");
}

$id_equipe = intval($_GET['id_equipe']);

// Ajouter un joueur
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_player'])) {
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $age = intval($_POST['age']);
    $passe = intval($_POST['passe']);
    $points = intval($_POST['points']);
    $faute = intval($_POST['faute']);
    $duel_gagne = intval($_POST['duel_gagne']);
    $titulaire = isset($_POST['titulaire']) ? 1 : 0;

    $query = "INSERT INTO joueurs (nom, age, id_equipe, Passe, Points, faute, duel_gagne, Titulaire)
              VALUES ('$nom', $age, $id_equipe, $passe, $points, $faute, $duel_gagne, $titulaire)";

    if (!mysqli_query($conn, $query)) {
        echo "<p style='color:red;'>Erreur lors de l'ajout du joueur: " . mysqli_error($conn) . "</p>";
    }
}

// Modifier un joueur
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_player'])) {
    $id_joueur = intval($_POST['id_joueur']);
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $age = intval($_POST['age']);
    $passe = intval($_POST['passe']);
    $points = intval($_POST['points']);
    $faute = intval($_POST['faute']);
    $duel_gagne = intval($_POST['duel_gagne']);
    $titulaire = isset($_POST['titulaire']) ? 1 : 0;

    $query = "UPDATE joueurs 
              SET nom='$nom', age=$age, Passe=$passe, Points=$points, faute=$faute, duel_gagne=$duel_gagne, Titulaire=$titulaire 
              WHERE id_joueur=$id_joueur";

    if (!mysqli_query($conn, $query)) {
        echo "<p style='color:red;'>Erreur lors de la modification du joueur: " . mysqli_error($conn) . "</p>";
    }
}

// Supprimer un joueur
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_player'])) {
    $id_joueur = intval($_POST['id_joueur']);

    $query = "DELETE FROM joueurs WHERE id_joueur=$id_joueur";

    if (!mysqli_query($conn, $query)) {
        echo "<p style='color:red;'>Erreur lors de la suppression du joueur: " . mysqli_error($conn) . "</p>";
    }
}

// Récupérer les joueurs de l'équipe
$query = "SELECT * FROM joueurs WHERE id_equipe = $id_equipe";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Joueurs</title>
    <link rel="stylesheet" href="modifier_joueurs.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="acceuil.php">Acceuil</a></li>
                <li><a href="Page_Modification.php">Équipes</a></li>
                <li><a href="gestion_joueurs.php?id_equipe=<?php echo $id_equipe; ?>" class="active">Joueurs</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Gérer les joueurs de l'équipe</h2>
        <h3>Ajouter un joueur</h3>
        <form method="POST">
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="number" name="age" placeholder="Âge" required>
            <input type="number" name="passe" placeholder="Passe">
            <input type="number" name="points" placeholder="Points">
            <input type="number" name="faute" placeholder="Faute">
            <input type="number" name="duel_gagne" placeholder="Duel Gagné">
            <label>
                <input type="checkbox" name="titulaire"> Titulaire
            </label>
            <button type="submit" name="add_player">Ajouter</button>
        </form>
        <hr>
        <h3>Liste des joueurs</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Âge</th>
                <th>Passe</th>
                <th>Points</th>
                <th>Faute</th>
                <th>Duel Gagné</th>
                <th>Titulaire</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <form method="POST">
                    <td><?php echo $row['id_joueur']; ?></td>
                    <td><input type="text" name="nom" value="<?php echo htmlspecialchars($row['nom']); ?>"></td>
                    <td><input type="number" name="age" value="<?php echo $row['age']; ?>"></td>
                    <td><input type="number" name="passe" value="<?php echo $row['Passe']; ?>"></td>
                    <td><input type="number" name="points" value="<?php echo $row['Points']; ?>"></td>
                    <td><input type="number" name="faute" value="<?php echo $row['faute']; ?>"></td>
                    <td><input type="number" name="duel_gagne" value="<?php echo $row['duel_gagne']; ?>"></td>
                    <td><input type="checkbox" name="titulaire" <?php echo $row['Titulaire'] ? 'checked' : ''; ?>></td>
                    <td>
                        <input type="hidden" name="id_joueur" value="<?php echo $row['id_joueur']; ?>">
                        <button type="submit" name="update_player">Modifier</button>
                        <button type="submit" name="delete_player" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce joueur ?');">Supprimer</button>
                    </td>
                </form>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <footer>
        <p>&copy; 2025 Gestion des Joueurs NBA</p>
    </footer>
</body>
</html>
<?php
// Fermer la connexion
mysqli_close($conn);
?>
