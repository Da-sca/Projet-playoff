<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TOP 5 Joueur - NBA Playoffs Pulse</title>
    <link rel="stylesheet" href="Classement_joueur.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="Acceuil_user.php">Accueil</a></li>
                <li><a href="visualisez_equipe.php">Équipes</a></li>
                <li><a href="Match_user.php">Matchs</a></li>
                <li><a href="Classement_joueur.php" class="active">TOP 5 joueur</a></li>
                <li><a href="Classement_equipe.php">Classement des équipes</a></li>
            </ul>
        </nav>
        <a href="Login.php" class="btn-connect">Se connecter</a>
    </header>

    <div class="container">
        <div class="content">
            <h1>TOP 5 Joueurs NBA</h1>
            <p>Découvrez les meilleurs joueurs des Playoffs NBA. Consultez les statistiques et les performances des joueurs les plus remarquables.</p>
        </div>
    </div>

    <div class="stats">
        <?php
        include 'functions.php';

        $conn = connectDatabase();

        function getTopPlayers($conn, $category) {
            $query = "
                SELECT j.nom AS joueur_nom, e.nom_equipe AS equipe_nom, s.$category
                FROM joueurs_stats s
                JOIN joueurs j ON s.id_joueur = j.id_joueur
                JOIN equipe e ON j.id_equipe = e.id_equipe
                ORDER BY s.$category DESC
                LIMIT 5";

            $result = mysqli_query($conn, $query);

            if (!$result) {
                die("<p style='color:red;'>Erreur dans la requête SQL: " . mysqli_error($conn) . "</p>");
            }

            return $result;
        }

        function displayTopPlayers($conn, $category, $label) {
            $result = getTopPlayers($conn, $category);

            echo "<h2>Top 5 $label</h2>";
            echo "<table>";
            echo "<tr>
                    <th>Joueur</th>
                    <th>Équipe</th>
                    <th>$label</th>
                  </tr>";

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['joueur_nom']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['equipe_nom']) . "</td>";
                    echo "<td>" . htmlspecialchars($row[$category]) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Aucun joueur trouvé</td></tr>";
            }

            echo "</table><br>";
        }

        displayTopPlayers($conn, 'points_par_match', 'Points par Match');
        displayTopPlayers($conn, 'passes_decisives', 'Passes Décisives');
        displayTopPlayers($conn, 'rebonds', 'Rebonds');
        displayTopPlayers($conn, 'interceptions', 'Interceptions');
        displayTopPlayers($conn, 'contres', 'Contres');
        displayTopPlayers($conn, 'fautes', 'Fautes');

        mysqli_close($conn);
        ?>
    </div>

    <footer>
        <p>&copy; 2025 NBA Playoffs Pulse</p>
    </footer>
</body>
</html>