<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

function automatic_match_Quart_de_final() {
    $host = "localhost";
    $user = "root";
    $passwd = "";
    $bdd = "playoff_try";
    $phase=2;

    $conn = mysqli_connect($host, $user, $passwd, $bdd);

    if (!$conn) {
        die("<p style='color:red;'>Erreur de connexion: " . mysqli_connect_error() . "</p>");
    }

    // Initialisation des tableaux
    $equipe_conference_ouest = [];
    $equipe_conference_est = [];

    // Récupérer les équipes qualifiées
    $query = "
    SELECT 
        CASE 
            WHEN Equipe1_victorieuse >= 4 THEN Equipe1
            ELSE Equipe2
        END AS selected_team,
        Conference
    FROM matches
    WHERE Equipe1_victorieuse >= 4 OR Equipe2_victorieuse >= 4;
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

    // Vérifier si nous avons bien 4 équipes dans chaque conférence
    if (count($equipe_conference_ouest) < 4 || count($equipe_conference_est) < 4) {
        die("<p style='color:red;'>Pas assez d'équipes pour créer les matchs</p>");
    }

    for ($i = 0; $i < 2; ++$i) { // Quarts de finale = 2 matchs par conférence
        // Matchs Conférence Ouest
        $stmt = mysqli_prepare($conn, "INSERT INTO matches (Equipe1, Equipe2, Conference, phase, Location_match) VALUES (?, ?, ?, ?, ?)");

        if ($stmt) {
            // Récupérer la ville de l'équipe
            $query_get_ville = "SELECT ville FROM equipe WHERE id_equipe = ?";
            $stmt_ville = mysqli_prepare($conn, $query_get_ville);
        
            if ($stmt_ville) {
                mysqli_stmt_bind_param($stmt_ville, "i", $equipe_conference_ouest[$i]);
                mysqli_stmt_execute($stmt_ville);
                mysqli_stmt_bind_result($stmt_ville, $ville);
                mysqli_stmt_fetch($stmt_ville);
                mysqli_stmt_close($stmt_ville);
            } else {
                $ville = "Inconnu"; // Valeur par défaut en cas d'erreur
            }
        
            // Insérer le match
            $conference = 0;
            mysqli_stmt_bind_param($stmt, "iiiis",
                $equipe_conference_ouest[$i], 
                $equipe_conference_ouest[3 - $i], 
                $conference, 
                $phase, 
                $ville
            );
        
            if (mysqli_stmt_execute($stmt)) {
            } else {
                echo "<p style='color:red;'>Erreur lors de l'ajout du match.</p>";
            }
        
            mysqli_stmt_close($stmt);
        }
        

        // Matchs Conférence Est
        $stmt = mysqli_prepare($conn, "INSERT INTO matches (Equipe1, Equipe2, Conference, phase, Location_match) VALUES (?, ?, ?, ?, ?)");

        if ($stmt) {
            // Récupérer la ville de l'équipe
            $query_get_ville = "SELECT ville FROM equipe WHERE id_equipe = ?";
            $stmt_ville = mysqli_prepare($conn, $query_get_ville);
        
            if ($stmt_ville) {
                mysqli_stmt_bind_param($stmt_ville, "i", $equipe_conference_est[$i]);
                mysqli_stmt_execute($stmt_ville);
                mysqli_stmt_bind_result($stmt_ville, $ville);
                mysqli_stmt_fetch($stmt_ville);
                mysqli_stmt_close($stmt_ville);
            } else {
                $ville = "Inconnu"; // Valeur par défaut si la ville ne peut pas être récupérée
            }
        
            // Insérer le match
            $conference = 1;
            mysqli_stmt_bind_param($stmt, "iiiis",
                $equipe_conference_est[$i], 
                $equipe_conference_est[3 - $i], 
                $conference, 
                $phase, 
                $ville
            );
        
            if (mysqli_stmt_execute($stmt)) {
            } else {
                echo "<p style='color:red;'>Erreur lors de l'ajout du match.</p>";
            }
        
            mysqli_stmt_close($stmt);
        }        
    }

    mysqli_close($conn);
}
?>
