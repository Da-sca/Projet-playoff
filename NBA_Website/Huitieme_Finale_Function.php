<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

function automatic_match_huitieme_de_final() {
    $host = "localhost";
    $user = "root";
    $passwd = "";
    $bdd = "playoff_try";
    $phase=1;

    $conn = mysqli_connect($host, $user, $passwd, $bdd);

    if (!$conn) {
        die("<p style='color:red;'>Erreur de connexion: " . mysqli_connect_error() . "</p>");
    }

    // Initialisation des tableaux
    $equipe_conference_ouest = [];
    $equipe_conference_est = [];

    // Recuperer les equipes triees par victoires
    $query = "SELECT id_equipe, nombre_de_victoire, Conference, ville FROM equipe ORDER BY nombre_de_victoire DESC";
    $result = mysqli_query($conn, $query);

    // Separer les equipes par conference
    while ($row = mysqli_fetch_array($result)) {
        if ($row["Conference"] == 0) {
            $equipe_conference_ouest[] = $row;
        } else {
            $equipe_conference_est[] = $row;
        }
    }

    // Verifier si nous avons bien 8 equipes dans chaque conference
    if (count($equipe_conference_ouest) < 8 || count($equipe_conference_est) < 8) {
        die("<p style='color:red;'>Pas assez d'equipes pour creer les matchs</p>");
    }

    for ($i = 0; $i < 4; ++$i) { // Huitième de finale = 4 matchs par conference
        // Matchs Conference Ouest
        $stmt = mysqli_prepare($conn, "INSERT INTO matches (Equipe1, Equipe2,Conference,phase,Location_match) VALUES (?, ?,?,?,?)");
        if ($stmt) {
            $conference=0;
            mysqli_stmt_bind_param($stmt, "iiiis",
                $equipe_conference_ouest[$i]['id_equipe'],
                $equipe_conference_ouest[7 - $i]['id_equipe'],
                $conference,
                $phase,
                $equipe_conference_ouest[$i]['ville']

            );
            if (mysqli_stmt_execute($stmt)) {
                    $equipe_conference_ouest[$i]['ville'] . " vs " . 
                    $equipe_conference_ouest[7 - $i]['ville'] . "</p>";
            }
            mysqli_stmt_close($stmt);
        }
    
        // Matchs Conference Est
        $stmt = mysqli_prepare($conn, "INSERT INTO matches (Equipe1, Equipe2,Conference,phase,Location_match) VALUES (?, ?,?,?,?)");
        if ($stmt) {
            $conference=1;

            mysqli_stmt_bind_param($stmt, "iiiis",
                $equipe_conference_est[$i]['id_equipe'],
                $equipe_conference_est[7 - $i]['id_equipe'],
                $conference,
                $phase,
                $equipe_conference_est[$i]['ville']

            );
            if (mysqli_stmt_execute($stmt)) {
                    $equipe_conference_est[$i]['ville'] . " vs " . 
                    $equipe_conference_est[7 - $i]['ville'] . "</p>";
            }
            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($conn);
}
?>
