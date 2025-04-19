<?php
include 'functions.php';

$conn = connectDatabase();

// Check if id_match is provided in the URL
if (isset($_GET['id_match']) && is_numeric($_GET['id_match'])) {
    $id_match = intval($_GET['id_match']);

    // Query to fetch player statistics for the given match
    $query = "
        SELECT 
            j.nom AS joueur_nom,
            e.nom_equipe AS equipe_nom,
            s.points_par_match,
            s.passes_decisives,
            s.rebonds,
            s.interceptions,
            s.contres,
            s.fautes
        FROM joueurs_stats s
        JOIN joueurs j ON s.id_joueur = j.id_joueur
        JOIN equipe e ON j.id_equipe = e.id_equipe
        WHERE s.id_match = ?
        ORDER BY e.nom_equipe, j.nom"; // Order by team name and player name
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_match);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo "<h1>Statistiques Détailées des Joueurs (Match ID: $id_match)</h1>";
        echo "<table border='1' cellspacing='0' cellpadding='8' style='border-collapse: collapse; width: 100%; text-align: center;'>";
        echo "<tr>
                <th>Joueur</th>
                <th>Équipe</th>
                <th>Points par Match</th>
                <th>Passes Décisives</th>
                <th>Rebonds</th>
                <th>Interceptions</th>
                <th>Contres</th>
                <th>Fautes</th>
              </tr>";

        $current_team = null;

        // Fetch and display the data
        while ($row = mysqli_fetch_assoc($result)) {
            // Check if the team has changed
            if ($current_team !== $row['equipe_nom']) {
                $current_team = $row['equipe_nom'];
                // Add a header row for the new team
                echo "<tr style='background-color: #f2f2f2; font-weight: bold;'>
                        <td colspan='8'>" . htmlspecialchars($current_team) . "</td>
                      </tr>";
            }

            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['joueur_nom']) . "</td>";
            echo "<td>" . htmlspecialchars($row['equipe_nom']) . "</td>";
            echo "<td>" . htmlspecialchars($row['points_par_match']) . "</td>";
            echo "<td>" . htmlspecialchars($row['passes_decisives']) . "</td>";
            echo "<td>" . htmlspecialchars($row['rebonds']) . "</td>";
            echo "<td>" . htmlspecialchars($row['interceptions']) . "</td>";
            echo "<td>" . htmlspecialchars($row['contres']) . "</td>";
            echo "<td>" . htmlspecialchars($row['fautes']) . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>Aucune statistique trouvée pour ce match.</p>";
    }

    mysqli_stmt_close($stmt);
} else {
    echo "<p>ID du match non valide ou manquant.</p>";
}

mysqli_close($conn);
?>