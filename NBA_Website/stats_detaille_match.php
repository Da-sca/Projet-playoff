<?php
include 'functions.php';

$conn = connectDatabase();

// Vérifier si l'ID du match est fourni dans l'URL
if (isset($_GET['id_match']) && is_numeric($_GET['id_match'])) {
    $id_match = intval($_GET['id_match']);

    // Requête pour récupérer les détails du match
    $query = "SELECT * FROM matchs_stats WHERE id_match = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_match);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Génération de l'affichage
    if (mysqli_num_rows($result) > 0) {
        echo "<!DOCTYPE html>
        <html lang='fr'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Détails du Match</title>
            <link rel='stylesheet' href='stats_detaille_match.css'>
        </head>
        <body>
        <header>
            <h1>Détails du Match (ID: $id_match)</h1>
        </header>
        <div class='container'>
        <table>
            <tr>
                <th>ID Match</th>
                <th>Saison</th>
                <th>Score Équipe 1</th>
                <th>Score Équipe 2</th>
                <th>Score Quart 1 (Équipe 1)</th>
                <th>Score Quart 1 (Équipe 2)</th>
                <th>Score Quart 2 (Équipe 1)</th>
                <th>Score Quart 2 (Équipe 2)</th>
                <th>Score Quart 3 (Équipe 1)</th>
                <th>Score Quart 3 (Équipe 2)</th>
                <th>Score Quart 4 (Équipe 1)</th>
                <th>Score Quart 4 (Équipe 2)</th>
                <th>ID Round</th>
                <th>Lieu</th>
                <th>Action</th>
            </tr>";

        // Affichage des données de chaque ligne
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                <td>" . htmlspecialchars($row['id_match']) . "</td>
                <td>" . htmlspecialchars($row['saison']) . "</td>
                <td>" . htmlspecialchars($row['score_equipe1']) . "</td>
                <td>" . htmlspecialchars($row['score_equipe2']) . "</td>
                <td>" . htmlspecialchars($row['score_quart_temps1_equipe1']) . "</td>
                <td>" . htmlspecialchars($row['score_quart_temps1_equipe2']) . "</td>
                <td>" . htmlspecialchars($row['score_quart_temps2_equipe1']) . "</td>
                <td>" . htmlspecialchars($row['score_quart_temps2_equipe2']) . "</td>
                <td>" . htmlspecialchars($row['score_quart_temps3_equipe1']) . "</td>
                <td>" . htmlspecialchars($row['score_quart_temps3_equipe2']) . "</td>
                <td>" . htmlspecialchars($row['score_quart_temps4_equipe1']) . "</td>
                <td>" . htmlspecialchars($row['score_quart_temps4_equipe2']) . "</td>
                <td>" . htmlspecialchars($row['id_round']) . "</td>
                <td>" . htmlspecialchars($row['Location_match']) . "</td>
                <td><a href='stat_detaille_joueur.php?id_match=" . htmlspecialchars($row['id_match']) . "'>Voir les Stats Joueurs</a></td>
                </tr>";
        }

        echo "</table></div>
        <footer>
            <p>&copy; 2025 NBA Match Details</p>
        </footer>
        </body>
        </html>";
    } else {
        echo "<p>Aucun détail trouvé pour ce match.</p>";
    }

    mysqli_stmt_close($stmt);
} else {
    echo "<p>ID du match non valide ou manquant.</p>";
}

mysqli_close($conn);
?>