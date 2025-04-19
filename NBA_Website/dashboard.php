<?php
session_start();
require_once('db_connect.php');

if (!isset($_SESSION['trainer_id'])) {
    header("Location: login.php");
    exit;
}

// Sa recupere l'equipe de l'entraineur
$stmt = $pdo->prepare("SELECT team_id FROM trainers WHERE id = ?");
$stmt->execute([$_SESSION['trainer_id']]);
// fetch assoc renvoie un tableau associatif
// fetch renvoie un tableau indexé
$trainer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$trainer) {
    die("Trainer not found!");
}

$coach_team_id = $trainer['team_id']; // Trainer's team ID

// Get team information
$stmt = $pdo->prepare("SELECT * FROM equipe WHERE id_equipe = ?");
$stmt->execute([$coach_team_id]);
$team = $stmt->fetch(PDO::FETCH_ASSOC);

// Get team players
$stmt = $pdo->prepare("SELECT * FROM joueurs WHERE Id_equipe = ?");
$stmt->execute([$coach_team_id]);
$players = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get upcoming matches
$stmt = $pdo->prepare("
    SELECT m.*, e1.nom_equipe as equipe1_nom, e2.nom_equipe as equipe2_nom 
    FROM matches m 
    JOIN equipe e1 ON m.Equipe1 = e1.id_equipe 
    JOIN equipe e2 ON m.Equipe2 = e2.id_equipe 
    WHERE (Equipe1 = ? OR Equipe2 = ?) 
    AND Date_match >= CURDATE() 
    ORDER BY Date_match ASC
");
$stmt->execute([$coach_team_id, $coach_team_id]);
$upcoming_matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Entraîneur</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <header>
        <nav>
            <ul>
            <li><a href="Acceuil_user.php" class="active">Acceuil</a></li>
                <li><a href="dashboard.php" class="active">Tableau de Bord</a></li>
            </ul>
        </nav>
    </header>

    <div class="header">
        <h1><?php echo htmlspecialchars($team['nom_equipe']); ?></h1>
    </div>

    <div class="team-stats">
        <div class="stat-box">
            <h3>Victoires</h3>
            <p><?php echo $team['nombre_de_victoire']; ?></p>
        </div>
        <div class="stat-box">
            <h3>Défaites</h3>
            <p><?php echo $team['nombre_de_defaite']; ?></p>
        </div>
        <div class="stat-box">
            <h3>Ville</h3>
            <p><?php echo htmlspecialchars($team['ville']); ?></p>
        </div>
    </div>
    
    <div class="dashboard">
        <div class="card">
            <div class="card-header">
                <h2>Effectif de l'Équipe</h2>
                <a href="manage_lineup.php?team=<?php echo $coach_team_id; ?>" class="button">
                    Gérer la composition d'équipe
                </a>
            </div>
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Points</th>
                    <th>Passes</th>
                    <th>Titulaire</th>
                    <th>Actions</th>
                </tr>
                <?php foreach($players as $player): ?>
                <tr>
                    <td><?php echo htmlspecialchars($player['nom']); ?></td>
                    <td><?php echo $player['Points']; ?></td>
                    <td><?php echo $player['Passe']; ?></td>
                    <td><?php echo $player['Titulaire'] ? 'Oui' : 'Non'; ?></td>
                    <td>
                        <a href="player_stats.php?player_id=<?php echo $player['id_joueur']; ?>">Voir les stats</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="card">
            <h2>Prochains Matchs</h2>
            <?php if (empty($upcoming_matches)): ?>
                <p>Aucun match à venir</p>
            <?php else: ?>
                <?php foreach($upcoming_matches as $match): ?>
                <div style="margin-bottom: 20px; padding: 15px; background: #fff; border-radius: 4px;">
                    <h3><?php echo htmlspecialchars($match['equipe1_nom']); ?> vs <?php echo htmlspecialchars($match['equipe2_nom']); ?></h3>
                    <p>Date: <?php echo date('d/m/Y', strtotime($match['Date_match'])); ?></p>
                    <p>Lieu: <?php echo htmlspecialchars($match['Location_match']); ?></p>
                    <a href="manage_lineup.php?match_id=<?php echo $match['id_match']; ?>&team=<?php echo $coach_team_id; ?>" class="button">
                        Définir la composition
                    </a>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Tableau de Bord Entraîneur</p>
    </footer>
</body>
</html>
