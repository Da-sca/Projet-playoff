<?php
//permet d'entrer les stats des joueurs (accessible à partir de la page match)
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host   = "localhost";
$user   = "root";
$passwd = "";
$bdd    = "playoff_try";

$conn = mysqli_connect($host, $user, $passwd, $bdd);

if (!$conn) {
    die("<p style='color:red;'>Erreur de connexion: " . mysqli_connect_error() . "</p>");
}

// Sanitize GET parameters
$id_equipe1 = isset($_GET['Equipe1']) ? mysqli_real_escape_string($conn, $_GET['Equipe1']) : '';
$id_equipe2 = isset($_GET['Equipe2']) ? mysqli_real_escape_string($conn, $_GET['Equipe2']) : '';
$id_match   = isset($_GET['id_match']) ? intval($_GET['id_match']) : 0;
$id_round   = isset($_GET['id_round']) ? intval($_GET['id_round']) : 0;

if (empty($id_equipe1) || empty($id_equipe2) || $id_match === 0) {
    die("Invalid request parameters.");
}

// Fetch teams and players
$query_equipe1 = "SELECT id_equipe FROM equipe WHERE nom_equipe = '$id_equipe1' LIMIT 1";
$query_equipe2 = "SELECT id_equipe FROM equipe WHERE nom_equipe = '$id_equipe2' LIMIT 1";
$result_equipe1 = mysqli_query($conn, $query_equipe1);
$result_equipe2 = mysqli_query($conn, $query_equipe2);

if (!$result_equipe1 || !$result_equipe2) {
    die("Erreur lors de la récupération des équipes: " . mysqli_error($conn));
}

$id_equipe1 = ($row = mysqli_fetch_assoc($result_equipe1)) ? $row['id_equipe'] : null;
$id_equipe2 = ($row = mysqli_fetch_assoc($result_equipe2)) ? $row['id_equipe'] : null;

if (!$id_equipe1 || !$id_equipe2) {
    die("Erreur: Une ou plusieurs équipes sont introuvables.");
}

$query_joueurs_equipe1 = "SELECT id_joueur, nom, age FROM joueurs WHERE id_equipe = '$id_equipe1'";
$query_joueurs_equipe2 = "SELECT id_joueur, nom, age FROM joueurs WHERE id_equipe = '$id_equipe2'";
$result_team1 = mysqli_query($conn, $query_joueurs_equipe1);
$result_team2 = mysqli_query($conn, $query_joueurs_equipe2);

if (!$result_team1 || !$result_team2) {
    die("Erreur lors de la récupération des joueurs: " . mysqli_error($conn));
}

// Insert player stats into joueurs_stats table (if not already inserted)
function insert_player_stats($conn, $id_joueur, $id_match, $id_round) {
    $query_check = "SELECT * FROM joueurs_stats WHERE id_joueur = '$id_joueur' AND id_match = '$id_match' AND id_round = '$id_round'";
    $result_check = mysqli_query($conn, $query_check);

    if (mysqli_num_rows($result_check) == 0) {
        $query_insert_stats = "INSERT INTO joueurs_stats (id_joueur, id_match, id_round) VALUES ('$id_joueur', '$id_match', '$id_round')";
        mysqli_query($conn, $query_insert_stats) or die("Erreur lors de l'insertion des stats: " . mysqli_error($conn));
    }
}

while ($row = mysqli_fetch_assoc($result_team1)) {
    insert_player_stats($conn, $row['id_joueur'], $id_match, $id_round);
}

while ($row = mysqli_fetch_assoc($result_team2)) {
    insert_player_stats($conn, $row['id_joueur'], $id_match, $id_round);
}

// Reset result pointers
mysqli_data_seek($result_team1, 0);
mysqli_data_seek($result_team2, 0);

// Process form submission for updating player stats
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST['points_par_match'] as $id_joueur => $points) {
        $points = intval($_POST['points_par_match'][$id_joueur]);
        $passes = intval($_POST['passes_decisives'][$id_joueur]);
        $rebonds = intval($_POST['rebonds'][$id_joueur]);
        $interceptions = intval($_POST['interceptions'][$id_joueur]);
        $contres = intval($_POST['contres'][$id_joueur]);
        $fautes = intval($_POST['fautes'][$id_joueur]);

        $query_update_stats = "UPDATE joueurs_stats 
                               SET points_par_match = '$points',
                                   passes_decisives = '$passes',
                                   rebonds = '$rebonds',
                                   interceptions = '$interceptions',
                                   contres = '$contres',
                                   fautes = '$fautes'
                               WHERE id_joueur = '$id_joueur' AND id_match = '$id_match' AND id_round = '$id_round'";

        if (!mysqli_query($conn, $query_update_stats)) {
            echo "<p style='color:red;'>Erreur lors de la mise à jour: " . mysqli_error($conn) . "</p>";
        } else {
            echo "<p style='color:green;'>Statistiques mises à jour pour le joueur ID: $id_joueur</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrer Stats Joueurs</title>
    <link rel="stylesheet" href="Modifier_stats_joueurs.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="acceuil.php">Accueil</a></li>
                <li><a href="match.php">Matchs</a></li>
                <li><a href="stats.php" class="active">Entrer Stats</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Statistiques des Joueurs</h2>
        <!-- Display player stats form -->
        <h2>Stats for Team 1:</h2>
        <form method='POST' action=''>
            <table border='1'>
                <tr>
                    <th>Player Name</th>
                    <th>Points per Match</th>
                    <th>Passes Decisives</th>
                    <th>Rebounds</th>
                    <th>Interceptions</th>
                    <th>Contres</th>
                    <th>Fautes</th>
                </tr>
                <?php
                while ($row = mysqli_fetch_assoc($result_team1)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['nom']) . " (Age: " . intval($row['age']) . ")</td>";
                    echo "<td><input type='number' name='points_par_match[" . $row['id_joueur'] . "]' value=''></td>";
                    echo "<td><input type='number' name='passes_decisives[" . $row['id_joueur'] . "]' value=''></td>";
                    echo "<td><input type='number' name='rebonds[" . $row['id_joueur'] . "]' value=''></td>";
                    echo "<td><input type='number' name='interceptions[" . $row['id_joueur'] . "]' value=''></td>";
                    echo "<td><input type='number' name='contres[" . $row['id_joueur'] . "]' value=''></td>";
                    echo "<td><input type='number' name='fautes[" . $row['id_joueur'] . "]' value=''></td>";
                    echo "</tr>";
                }
                ?>
            </table>

            <h2>Stats for Team 2:</h2>
            <table border='1'>
                <?php
                while ($row = mysqli_fetch_assoc($result_team2)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['nom']) . " (Age: " . intval($row['age']) . ")</td>";
                    echo "<td><input type='number' name='points_par_match[" . $row['id_joueur'] . "]' value=''></td>";
                    echo "<td><input type='number' name='passes_decisives[" . $row['id_joueur'] . "]' value=''></td>";
                    echo "<td><input type='number' name='rebonds[" . $row['id_joueur'] . "]' value=''></td>";
                    echo "<td><input type='number' name='interceptions[" . $row['id_joueur'] . "]' value=''></td>";
                    echo "<td><input type='number' name='contres[" . $row['id_joueur'] . "]' value=''></td>";
                    echo "<td><input type='number' name='fautes[" . $row['id_joueur'] . "]' value=''></td>";
                    echo "</tr>";
                }
                ?>
            </table>
            <input type='submit' value='Update Stats'>
        </form>
    </div>

    <footer>
        <p>&copy; 2025 NBA Stats Management</p>
    </footer>
</body>
</html>

<?php
mysqli_close($conn);
?>
