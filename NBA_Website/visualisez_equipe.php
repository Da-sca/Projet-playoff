<?php
include 'functions.php';
$conn = connectDatabase();

$query = "SELECT * FROM equipe";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Équipes</title>
    <link rel="stylesheet" href="visualisez_equipe.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="Acceuil_user.php">Accueil</a></li>
                <li><a href="Match_user.php">Matchs</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Liste des Équipes</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nom de l'équipe</th>
                <th>Actions</th>
            </tr>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <form method='POST' action=''>
                            <td>" . $row['id_equipe'] . "</td>
                            <td><input type='text' name='nom_equipe' value='" . htmlspecialchars($row['nom_equipe']) . "' required></td>
                            <td>
                                <input type='hidden' name='id_equipe' value='" . $row['id_equipe'] . "'>
                                <a href='visualisez_joueur_equipe.php?id_equipe=" . htmlspecialchars($row['id_equipe']) . "'>Afficher stat Joueurs</a>
                            </td>
                        </form>
                      </tr>";
            }
            ?>
        </table>
    </div>

    <footer>
        <p>&copy; 2025 Gestion des Équipes</p>
    </footer>
</body>
</html>
