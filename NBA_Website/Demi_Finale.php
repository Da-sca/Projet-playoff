<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

function automatic_match_Demi_final() {
    $host = "localhost";
    $user = "root";
    $passwd = "";
    $bdd = "playoff_try";
    $phase = 3;

    $conn = mysqli_connect($host, $user, $passwd, $bdd);

    if (!$conn) {
        die("<p style='color:red;'>Erreur de connexion: " . mysqli_connect_error() . "</p>");
    }

    // Initialisation des tableaux
    $equipe_conference_ouest = [];
    $equipe_conference_est = [];

    // Récupérer les équipes qualifiées
    $query = "
    SELECT e.id_equipe AS selected_team, e.nom_equipe, e.ville, e.Conference
    FROM (
        SELECT Equipe1 AS selected_team, Conference
        FROM matches
        WHERE Equipe1_victorieuse >= 4

        UNION ALL

        SELECT Equipe2 AS selected_team, Conference
        FROM matches
        WHERE Equipe2_victorieuse >= 4
    ) AS all_winning_matches
    JOIN equipe e ON e.id_equipe = all_winning_matches.selected_team
    GROUP BY e.id_equipe, e.nom_equipe, e.ville, e.Conference
    HAVING COUNT(*) >= 2;
    ";

    $result = mysqli_query($conn, $query);

    // Séparer les équipes par conférence
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row["Conference"] == 0) {
            $equipe_conference_ouest[] = $row["selected_team"];
        } else {
            $equipe_conference_est[] = $row["selected_team"];
        }
    }

    // Vérifier s'il y a assez d'équipes
    if (count($equipe_conference_ouest) < 2 || count($equipe_conference_est) < 2) {
        die("<p style='color:red;'>Pas assez d'équipes pour créer les matchs</p>");
    }

    for ($i = 0; $i < 1; ++$i) { 
        // Récupérer la ville de l'équipe Conférence Ouest
        $stmt_ville = mysqli_prepare($conn, "SELECT ville FROM equipe WHERE id_equipe = ?");
        $ville_ouest = "Inconnu";
        if ($stmt_ville) {
            mysqli_stmt_bind_param($stmt_ville, "i", $equipe_conference_ouest[$i]);
            mysqli_stmt_execute($stmt_ville);
            mysqli_stmt_bind_result($stmt_ville, $ville_ouest);
            mysqli_stmt_fetch($stmt_ville);
            mysqli_stmt_close($stmt_ville);
        }

        // Insérer le match Conférence Ouest
        $stmt = mysqli_prepare($conn, "INSERT INTO matches (Equipe1, Equipe2, Conference, phase, Location_match) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $conference = 0;
            mysqli_stmt_bind_param($stmt, "iiiis",
                $equipe_conference_ouest[$i], 
                $equipe_conference_ouest[1], 
                $conference,
                $phase,
                $ville_ouest
            );
            if (mysqli_stmt_execute($stmt)) {
            }
            mysqli_stmt_close($stmt);
        }

        // Récupérer la ville de l'équipe Conférence Est
        $stmt_ville = mysqli_prepare($conn, "SELECT ville FROM equipe WHERE id_equipe = ?");
        $ville_est = "Inconnu";
        if ($stmt_ville) {
            mysqli_stmt_bind_param($stmt_ville, "i", $equipe_conference_est[$i]);
            mysqli_stmt_execute($stmt_ville);
            mysqli_stmt_bind_result($stmt_ville, $ville_est);
            mysqli_stmt_fetch($stmt_ville);
            mysqli_stmt_close($stmt_ville);
        }

        // Insérer le match Conférence Est
        $stmt = mysqli_prepare($conn, "INSERT INTO matches (Equipe1, Equipe2, Conference, phase, Location_match) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $conference = 1;
            mysqli_stmt_bind_param($stmt, "iiiis",
                $equipe_conference_est[$i], 
                $equipe_conference_est[1], 
                $conference,
                $phase,
                $ville_est
            );
            if (mysqli_stmt_execute($stmt)) {
            }
            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($conn);
}
?>
