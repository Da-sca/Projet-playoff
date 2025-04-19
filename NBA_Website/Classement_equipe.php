<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classement des Équipes - NBA Playoffs Pulse</title>
    <link rel="stylesheet" href="Classement_equipe.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="Acceuil_user.php">Accueil</a></li>
                <li><a href="visualisez_equipe.php">Équipes</a></li>
                <li><a href="Match_user.php">Matchs</a></li>
                <li><a href="Classement_joueur.php">TOP 5 joueur</a></li>
                <li><a href="Classement_equipe.php" class="active">Classement des équipes</a></li>
            </ul>
        </nav>
        <a href="Login.php" class="btn-connect">Se connecter</a>
    </header>

    <div class="container">
        <div class="content">
            <h1>Classement des Équipes NBA</h1>
            <p>Découvrez le classement des équipes participant aux Playoffs NBA. Consultez les statistiques et les performances des équipes les plus remarquables.</p>
        </div>
    </div>

    <div class="stats">
        <?php
        include 'functions.php';

        $conn = connectDatabase();

        // Query to fetch teams grouped by phase, ordered by number of wins, and filtering teams with less than 4 victories
        $query = "
            SELECT 
                e.nom_equipe,
                e.ville,
                e.nombre_de_victoire,
                e.nombre_de_defaite,
                m.phase
            FROM equipe e
            JOIN matches m ON (e.id_equipe = m.Equipe1 OR e.id_equipe = m.Equipe2)
            WHERE 
                (e.id_equipe = m.Equipe1 AND m.Equipe1_victorieuse < 4) OR 
                (e.id_equipe = m.Equipe2 AND m.Equipe2_victorieuse < 4)
            GROUP BY m.phase, e.id_equipe
            ORDER BY m.phase ASC, e.nombre_de_victoire DESC";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            echo "<h1>Classement des Équipes par Phase </h1>";
            echo "<table border='1' cellspacing='0' cellpadding='8' style='border-collapse: collapse; width: 100%; text-align: center;'>";
            echo "<tr>
                    <th>Phase</th>
                    <th>Équipe</th>
                    <th>Ville</th>
                    <th>Nombre de Victoires</th>
                    <th>Nombre de Défaites</th>
                  </tr>";

            $current_phase = null;

            // Fetch and display the data
            while ($row = mysqli_fetch_assoc($result)) {
                // Check if the phase has changed
                if ($current_phase !== $row['phase']) {
                    $current_phase = $row['phase'];
                    // Add a header row for the new phase
                    echo "<tr style='background-color: #f2f2f2; font-weight: bold;'>
                            <td colspan='5'>Phase: " . htmlspecialchars($current_phase) . "</td>
                          </tr>";
                }

                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['phase']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nom_equipe']) . "</td>";
                echo "<td>" . htmlspecialchars($row['ville']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nombre_de_victoire']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nombre_de_defaite']) . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>Aucune équipe trouvée.</p>";
        }

        mysqli_close($conn);
        ?>
    </div>

    <footer>
        <p>&copy; 2025 NBA Playoffs Pulse</p>
    </footer>
</body>
</html>
