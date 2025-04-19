<?php
session_start();
require_once('db_connect.php');

$coach_team_id = isset($_GET['team']) ? intval($_GET['team']) : 1;
$player_id = isset($_GET['player_id']) ? intval($_GET['player_id']) : 10;

// sa recupere les stats du joueur
$stmt = $pdo->prepare("
    SELECT 
        j.*,
        js.points_par_match,
        js.passes_decisives,
        js.rebonds,
        js.interceptions,
        js.contres,
        js.Fautes,
        m.Date_match
    FROM joueurs j
    LEFT JOIN joueurs_stats js ON j.id_joueur = js.id_joueur
    LEFT JOIN matches m ON js.id_match = m.id_match
    WHERE j.id_joueur = ? AND j.Id_equipe = ?
    ORDER BY m.Date_match DESC
    LIMIT 5
");
$stmt->execute([$player_id, $coach_team_id]);
$stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

//sa recupere son nom a partir de son id
$stmt = $pdo->prepare("SELECT nom FROM joueurs WHERE id_joueur = ?");
$stmt->execute([$player_id]);
$player = $stmt->fetch(PDO::FETCH_ASSOC);

// sa calcule les moyennes des stats du joueur
$stmt = $pdo->prepare("
    SELECT 
        AVG(points_par_match) as avg_points,
        AVG(passes_decisives) as avg_passes,
        AVG(rebonds) as avg_rebonds,
        AVG(interceptions) as avg_interceptions,
        AVG(contres) as avg_contres,
        AVG(Fautes) as avg_fautes
    FROM joueurs_stats
    WHERE id_joueur = ?
");
$stmt->execute([$player_id]);
$averages = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques du Joueur</title>
    <link rel="stylesheet" href="player_stats.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="dashboard.php?team=<?php echo $coach_team_id; ?>" class="back-link">←</a>
            <h1>Statistiques de <?php echo htmlspecialchars($player['nom']); ?></h1>
        </div>

        <?php if ($averages): ?>
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Points par match</h3>
                <p><?php echo number_format($averages['avg_points'], 1); ?></p>
            </div>
            <div class="stat-card">
                <h3>Passes décisives</h3>
                <p><?php echo number_format($averages['avg_passes'], 1); ?></p>
            </div>
            <div class="stat-card">
                <h3>Rebonds</h3>
                <p><?php echo number_format($averages['avg_rebonds'], 1); ?></p>
            </div>
            <div class="stat-card">
                <h3>Interceptions</h3>
                <p><?php echo number_format($averages['avg_interceptions'], 1); ?></p>
            </div>
            <div class="stat-card">
                <h3>Contres</h3>
                <p><?php echo number_format($averages['avg_contres'], 1); ?></p>
            </div>
            <div class="stat-card">
                <h3>Fautes</h3>
                <p><?php echo number_format($averages['avg_fautes'], 1); ?></p>
            </div>
        </div>
        <?php endif; ?>

        <div class="matches-card">
            <h2>5 derniers matchs</h2>
            <?php if (!empty($stats)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Points</th>
                            <th>Passes</th>
                            <th>Rebonds</th>
                            <th>Interceptions</th>
                            <th>Contres</th>
                            <th>Fautes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($stats as $stat): ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($stat['Date_match'])); ?></td>
                            <td><?php echo $stat['points_par_match']; ?></td>
                            <td><?php echo $stat['passes_decisives']; ?></td>
                            <td><?php echo $stat['rebonds']; ?></td>
                            <td><?php echo $stat['interceptions']; ?></td>
                            <td><?php echo $stat['contres']; ?></td>
                            <td><?php echo $stat['Fautes']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Aucune statistique disponible pour ce joueur.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>