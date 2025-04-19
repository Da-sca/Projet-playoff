<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

function automatic_match_Final() {
    $host = "localhost";
    $user = "root";
    $passwd = "";
    $bdd = "playoff_try";
    $phase = 4;

    $conn = mysqli_connect($host, $user, $passwd, $bdd);

    if (!$conn) {
        die("<p style='color:red;'>Erreur de connexion: " . mysqli_connect_error() . "</p>");
    }

    // Initialisation des variables
    $equipe_ouest = null;
    $equipe_est = null;

    // Récupérer les équipes qualifiées (1 par conférence)
    $query = "
    SELECT selected_team, Conference
    FROM (
        SELECT Equipe1 AS selected_team, Conference
        FROM matches
        WHERE Equipe1_victorieuse >= 4

        UNION ALL

        SELECT Equipe2 AS selected_team, Conference
        FROM matches
        WHERE Equipe2_victorieuse >= 4
    ) AS all_winning_matches
    GROUP BY selected_team, Conference
    HAVING COUNT(*) >= 3;
    ";

    $result = mysqli_query($conn, $query);

    // Séparer les équipes par conférence
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row["Conference"] == 0) {
            $equipe_ouest = $row["selected_team"];
        } else {
            $equipe_est = $row["selected_team"];
        }
    }

    // Vérifier si nous avons une équipe de chaque conférence
    if (!$equipe_ouest || !$equipe_est) {
        die("<p style='color:red;'>Pas assez d'équipes pour créer la finale</p>");
    }

    // Récupérer la ville de l'équipe de la Conférence Ouest (Lieu du match)
    $stmt_ville = mysqli_prepare($conn, "SELECT ville FROM equipe WHERE id_equipe = ?");
    $location_match = "Inconnu";
    if ($stmt_ville) {
        mysqli_stmt_bind_param($stmt_ville, "i", $equipe_ouest);
        mysqli_stmt_execute($stmt_ville);
        mysqli_stmt_bind_result($stmt_ville, $location_match);
        mysqli_stmt_fetch($stmt_ville);
        mysqli_stmt_close($stmt_ville);
    }

    // Insérer le match de la finale
    $stmt = mysqli_prepare($conn, "INSERT INTO matches (Equipe1, Equipe2, Conference, phase, Location_match) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        $conference = 2; // 2 pour la finale
        mysqli_stmt_bind_param($stmt, "iiiis",
            $equipe_ouest, 
            $equipe_est, 
            $conference,
            $phase,
            $location_match
        );

        if (mysqli_stmt_execute($stmt)) {
        } else {
            echo "<p style='color:red;'>Erreur lors de l'ajout de la finale.</p>";
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
}
?>
