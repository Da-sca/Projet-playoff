<?php
include 'functions.php';
include 'Huitieme_Finale_Function.php';

$conn = connectDatabase();
$result = getMatches_user_huitieme($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Huitièmes de Finale - Utilisateur</title>
    <link rel="stylesheet" href="User_match_huitieme.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="Acceuil_user.php">Accueil</a></li>
                <li><a href="visualisez_equipe.php">Équipes</a></li>
                <li><a href="Match_user.php">Matchs</a></li>
                <li><a href="Classement_user.php">Classement</a></li>
                <li><a href="User_match_huitieme.php" class="active">Huitièmes de Finale</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h1>Huitièmes de Finale</h1>
        <?php renderMatches_user($conn, $result); ?>
    </div>

    <footer>
        <p>&copy; 2025 NBA Playoffs</p>
    </footer>
</body>
</html>

<?php
mysqli_close($conn);
?>
