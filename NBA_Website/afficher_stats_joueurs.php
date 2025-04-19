<?php
// Affiche les stats des joueurs
include 'functions.php';
$conn = connectDatabase();

$id_joueurs = $_GET['id_joueurs']; // ID du joueur passé dans l'URL

// Fetch the player's name
$query_player_name = "SELECT nom FROM joueurs WHERE id_joueur = $id_joueurs";
$result_player_name = mysqli_query($conn, $query_player_name);
$player_name = "Inconnu"; // Default value if no name is found

if ($result_player_name && mysqli_num_rows($result_player_name) > 0) {
    $row_player_name = mysqli_fetch_assoc($result_player_name);
    $player_name = htmlspecialchars($row_player_name['nom']);
}

// Fetch the player's stats
$query_stat_joueurs = "SELECT * FROM joueurs_stats WHERE id_joueur = $id_joueurs";
$result = mysqli_query($conn, $query_stat_joueurs);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques du Joueur</title>
    <link rel="stylesheet" href="afficher_stats_joueurs.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="Acceuil_user.php">Accueil</a></li>
                <li><a href="visualisez_equipes.php">Équipes</a></li>
                <li><a href="match.php">Matchs</a></li>
                <li><a href="stats_joueur.php?id_joueurs=<?= htmlspecialchars($id_joueurs); ?>" class="active">Statistiques</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2> Joueur: <?= $player_name; ?></h2>

        <table>
            <tr>
                <th>Saison</th>
                <th>Points par Match</th>
                <th>Passes Décisives</th>
                <th>Rebonds</th>
                <th>Interceptions</th>
                <th>Contres</th>
                <th>ID Match</th>
                <th>Fautes</th>
                <th>ID Round</th>
            </tr>

            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['saison']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['points_par_match']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['passes_decisives']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['rebonds']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['interceptions']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['contres']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['id_match']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Fautes']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['id_round']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>Aucune statistique trouvée pour ce joueur.</td></tr>";
            }
            ?>
        </table>
    </div>

    <footer>
        <p>&copy; 2025 Statistiques NBA</p>
    </footer>
</body>
</html>
