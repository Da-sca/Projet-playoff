<?php
session_start();
require_once('db_connect.php');

$coach_team_id = isset($_GET['team']) ? intval($_GET['team']) : 1;
$match_id = isset($_GET['match_id']) ? intval($_GET['match_id']) : null;

// Obtenir des informations sur l'équipe
$stmt = $pdo->prepare("SELECT nom_equipe FROM equipe WHERE id_equipe = ?");
$stmt->execute([$coach_team_id]);
$team = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtenir les joueurs de l'équipe
$stmt = $pdo->prepare("SELECT * FROM joueurs WHERE Id_equipe = ?");
$stmt->execute([$coach_team_id]);
$players = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Messages de succès ou d'erreur
$success_message = '';
$error_message = '';

// Soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['lineup']) || count($_POST['lineup']) !== 5) {
        $error_message = "Vous devez sélectionner exactement 5 joueurs pour le lineup.";
    } else {
        try {
            $pdo->beginTransaction();

            // Mise à jour des statuts titulaires
            $stmt = $pdo->prepare("UPDATE joueurs SET Titulaire = 0 WHERE Id_equipe = ?");
            $stmt->execute([$coach_team_id]);

            $stmt = $pdo->prepare("UPDATE joueurs SET Titulaire = 1 WHERE id_joueur = ? AND Id_equipe = ?");
            foreach ($_POST['lineup'] as $player_id) {
                $stmt->execute([$player_id, $coach_team_id]);
            }

            if ($match_id) {
                $stmt = $pdo->prepare("DELETE FROM lineup WHERE id_match = ?");
                $stmt->execute([$match_id]);

                $stmt = $pdo->prepare("INSERT INTO lineup (id_joueur, id_match) VALUES (?, ?)");
                foreach ($_POST['lineup'] as $player_id) {
                    $stmt->execute([$player_id, $match_id]);
                }
            }

            $pdo->commit();
            $success_message = "Lineup modifié avec succès";
            header("refresh:2;url=dashboard.php?team=" . $coach_team_id);
        } catch (Exception $e) {
            $pdo->rollBack();
            $error_message = "Erreur lors de la mise à jour de la composition: " . $e->getMessage();
        }
    }
}

// Récupérer le lineup actuel
$current_lineup = [];
if ($match_id) {
    $stmt = $pdo->prepare("SELECT id_joueur FROM lineup WHERE id_match = ?");
    $stmt->execute([$match_id]);
    $current_lineup = $stmt->fetchAll(PDO::FETCH_COLUMN);
} else {
    $stmt = $pdo->prepare("SELECT id_joueur FROM joueurs WHERE Id_equipe = ? AND Titulaire = 1");
    $stmt->execute([$coach_team_id]);
    $current_lineup = $stmt->fetchAll(PDO::FETCH_COLUMN);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer la Composition</title>
    <link rel="stylesheet" href="manage_lineup.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                
                <li><a href="dashboard.php?team=<?php echo $coach_team_id; ?>">Tableau de Bord</a></li>
                <li><a href="lineup.php?team=<?php echo $coach_team_id; ?>" class="active">Composition</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h1>Gérer la Composition - <?php echo htmlspecialchars($team['nom_equipe']); ?></h1>

        <?php if ($success_message): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php elseif ($error_message): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="player-list">
                <?php foreach($players as $player): ?>
                <div class="player-item">
                    <strong><?php echo htmlspecialchars($player['nom']); ?></strong>
                    <p>Points : <?php echo $player['Points']; ?></p>
                    <p>Passes : <?php echo $player['Passe']; ?></p>
                    <p>Duels gagnés : <?php echo $player['duel_gagne']; ?></p>
                    <input type="checkbox" 
                           name="lineup[]" 
                           value="<?php echo $player['id_joueur']; ?>"
                           <?php echo in_array($player['id_joueur'], $current_lineup) ? 'checked' : ''; ?>>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="submit">Sauvegarder la Composition</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2025 NBA Management Tool</p>
    </footer>
</body>
</html>