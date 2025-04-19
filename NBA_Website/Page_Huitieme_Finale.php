<?php
include 'functions.php';
include 'Huitieme_Finale_Function.php';

$conn = connectDatabase();
checkAndCreateMatches($conn, 8, 'automatic_match_huitieme_de_final');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_match'])) {
    $quart_scores = [
        'q1_e1' => intval($_POST['q1_e1']),
        'q1_e2' => intval($_POST['q1_e2']),
        'q2_e1' => intval($_POST['q2_e1']),
        'q2_e2' => intval($_POST['q2_e2']),
        'q3_e1' => intval($_POST['q3_e1']),
        'q3_e2' => intval($_POST['q3_e2']),
        'q4_e1' => intval($_POST['q4_e1']),
        'q4_e2' => intval($_POST['q4_e2']),
    ];

    $winner = isset($_POST['winner']) ? $_POST['winner'] : null;

    updateMatch(
        $conn,
        intval($_POST['match_id']),
        $_POST['Date_match'],
        intval($_POST['id_arbitre']),
        intval($_POST['score_equipe1']),
        intval($_POST['score_equipe2']),
        $winner,
        $quart_scores
    );
}

$result = getMatches($conn, 1);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Huitième de Finale</title>
    <link rel="stylesheet" href="Page_Huitieme_Finale.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="acceuil.php">Accueil</a></li>
                <li><a href="gestion_matches.php" class="active">Huitième de Finale</a></li>
                <li><a href="classement.php">Classements</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h1>Huitième de Finale</h1>
        <form method="POST">
            <?php
            displayMatches($result, $conn);
            mysqli_close($conn);
            ?>
        </form>
    </div>

    <footer>
        <p>&copy; 2025 NBA Playoffs Pulse</p>
    </footer>
</body>
</html>
