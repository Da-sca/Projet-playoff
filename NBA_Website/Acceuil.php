<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - NBA Playoffs Pulse</title>
    <link rel="stylesheet" href="Acceuil.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="acceuil.php" class="active">Accueil</a></li>
                <li><a href="Page_Modification.php">Modifier</a></li>
                <li><a href="visualisez_equipe.php">Équipes</a></li>
                <li><a href="match.php">Matchs</a></li>
                <li><a href="Classement_joueur.php">TOP 5 joueur</a></li>
                <li><a href="Classement_equipe.php">Classement des équipes</a></li>
                <li><a href="Modifier_compte.php">Modifier les comptes</a></li>
                <li><a href="Modifier_arbitre.php">Gérer Arbitre</a></li>
            </ul>
        </nav>
        <a href="Login.php" class="btn-connect">Se connecter</a>
    </header>

    <div class="container">
        <div class="content">
            <h1>NBA Playoffs Pulse</h1>
            <p>Suivez chaque dunk, chaque tir décisif et chaque moment inoubliable des Playoffs NBA. Scores en direct, classements actualisés et analyses exclusives – ne manquez rien de l'intensité des Playoffs !</p>
        </div>
        <img src="img_acceuil.png" alt="Joueur Dunk" class="dunk-player">
    </div>

    <div class="cards">
        <div class="card" style="grid-area: card1;">
            <img src="img_match.jpg" alt="Matchs">
            <a href="match.php">Matchs</a>
        </div>
        <div class="card" style="grid-area: card2;">
            <img src="img_stat.jpg" alt="Statistiques">
            <a href="stats.php">Statistiques</a>
        </div>
        <div class="card" style="grid-area: card3;">
            <img src="img_class.jpg" alt="Classement">
            <a href="classement.php">Classement</a>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 NBA Playoffs Pulse</p>
    </footer>
</body>
</html>
