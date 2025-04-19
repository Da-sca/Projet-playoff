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

// Ajouter un user
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_entraineur'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password
    $team_id = intval($_POST['team_id']);
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    $query = "INSERT INTO trainers (username, password, team_id, is_admin)
              VALUES ('$username', '$password', $team_id, $is_admin)";

    if (!mysqli_query($conn, $query)) {
        echo "<p style='color:red;'>Erreur lors de l'ajout de l'entraineur: " . mysqli_error($conn) . "</p>";
    } else {
        echo "<p style='color:green;'>Entraineur ajouté avec succès.</p>";
    }
}

// Modifier un entraineur
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_entraineur'])) {
    $id = intval($_POST['id']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $team_id = intval($_POST['team_id']);
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    // Check if a new password is provided
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the new password
        $query = "UPDATE trainers 
                  SET username='$username', password='$password', team_id=$team_id, is_admin=$is_admin 
                  WHERE id=$id";
    } else {
        $query = "UPDATE trainers 
                  SET username='$username', team_id=$team_id, is_admin=$is_admin 
                  WHERE id=$id";
    }

    if (!mysqli_query($conn, $query)) {
        echo "<p style='color:red;'>Erreur lors de la modification de l'entraineur: " . mysqli_error($conn) . "</p>";
    } else {
        echo "<p style='color:green;'>Entraineur modifié avec succès.</p>";
    }
}

// Supprimer un entraineur
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_entraineur'])) {
    $id = intval($_POST['id']);

    $query = "DELETE FROM trainers WHERE id=$id";

    if (!mysqli_query($conn, $query)) {
        echo "<p style='color:red;'>Erreur lors de la suppression de l'entraineur: " . mysqli_error($conn) . "</p>";
    } else {
        echo "<p style='color:green;'>Entraineur supprimé avec succès.</p>";
    }
}

// Récupérer les trainers
$query = "SELECT * FROM trainers";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Entraineurs</title>
    <link rel="stylesheet" href="Modifier_compte.css">
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
        <h2>Gérer les Entraineurs</h2>
        <h3>Ajouter un Entraineur</h3>
        <form method="POST" action="">
            <label>Nom d'utilisateur: <input type="text" name="username" required></label><br>
            <label>Mot de passe: <input type="password" name="password" required></label><br>
            <label>ID de l'équipe: <input type="number" name="team_id"></label><br>
            <label>Admin: <input type="checkbox" name="is_admin"></label><br>
            <button type="submit" name="add_entraineur">Ajouter</button>
        </form>
        <hr>
        <h3>Liste des Entraineurs</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Nom d'utilisateur</th>
                <th>ID de l'équipe</th>
                <th>Admin</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <form method="POST" action="">
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><input type="text" name="username" value="<?php echo htmlspecialchars($row['username']); ?>" required></td>
                    <td><input type="number" name="team_id" value="<?php echo $row['team_id']; ?>"></td>
                    <td><input type="checkbox" name="is_admin" <?php echo $row['is_admin'] ? 'checked' : ''; ?>></td>
                    <td>
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <label>Nouveau mot de passe: <input type="password" name="password"></label><br>
                        <button type="submit" name="update_entraineur">Modifier</button>
                        <button type="submit" name="delete_entraineur" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet entraineur ?');">Supprimer</button>
                    </td>
                </form>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <footer></footer>
        <p>&copy; 2025 Gestion des Entraineurs NBA</p>
    </footer>
</body>
</html>
<?php
// Fermer la connexion
mysqli_close($conn);
?>