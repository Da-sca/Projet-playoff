<?php
include 'functions.php';
$conn = connectDatabase();

$id_equipe = $_GET['id_equipe'];
$query_joueur = "SELECT * FROM joueurs WHERE id_equipe = $id_equipe";
$result = mysqli_query($conn, $query_joueur);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Joueurs Stats</title>
    <link rel="stylesheet" href="visualisez_joueur_equipe.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="acceuil.php">Accueil</a></li>
                <li><a href="visualisez_joueur_equipe.php?id_equipe=<?= $id_equipe ?>" class="active">Liste des joueurs</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Liste des Joueurs</h2>

        <table>
            <tr>
                <th>Nom</th>
                <th>Âge</th>
                <th>Voir Stats</th>
            </tr>

            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['nom']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['age']) . "</td>";
                    echo "<td><a href='afficher_stats_joueurs.php?id_joueurs=" . htmlspecialchars($row['id_joueur']) . "'>Afficher stats Joueur</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Aucun joueur trouvé.</td></tr>";
            }
            ?>
        </table>
    </div>

    <footer>
        <p>&copy; 2025 Stats des Joueurs</p>
    </footer>
</body>
</html>
