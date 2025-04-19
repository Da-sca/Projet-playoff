<?php

include 'connect.php';

$sql = "SELECT e.id_equipe, e.nom_equipe, e.ville, e.nombre_de_victoire, e.nombre_de_defaite, 
        (e.nombre_de_victoire + e.nombre_de_defaite) AS matches_joues,
        e.Conference,
        CASE 
            WHEN e.Conference = 0 THEN 'Ouest'
            WHEN e.Conference = 1 THEN 'Est'
            ELSE 'Inconnue'
        END AS nom_conference
        FROM equipe e
        ORDER BY e.Conference, e.nombre_de_victoire DESC, e.nombre_de_defaite ASC";

$resultat = mysqli_query($conn, $sql);

$equipes_est = [];
$equipes_ouest = [];

if ($resultat && mysqli_num_rows($resultat) > 0) {
    while ($equipe = mysqli_fetch_assoc($resultat)) {
        if ($equipe['Conference'] == 1) {
            $equipes_est[] = $equipe;
        } else {
            $equipes_ouest[] = $equipe;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classement NBA</title>
    <link rel="stylesheet" href="classement.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="acceuil.php">Accueil</a></li>
                <li><a href="Page_Modification.php">Modifier</a></li>
                <li><a href="visualisez_equipe.php">Équipes</a></li>
                <li><a href="match.php">Matchs</a></li>
                <li><a href="classement.php" class="active">Classement</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h1>Classement NBA</h1>
        
        <h2>Conférence Est</h2>
        <div class="table-container">
            <table>
                <tr>
                    <th>Position</th>
                    <th>Équipe</th>
                    <th>Matches joués</th>
                    <th>Victoires</th>
                    <th>Défaites</th>
                    <th>% Victoires</th>
                </tr>
                <?php
                $position = 1;
                foreach ($equipes_est as $equipe) {
                    $pourcentage = round(($equipe['nombre_de_victoire'] / $equipe['matches_joues']) * 100, 1);
                    echo "<tr>
                            <td>{$position}</td>
                            <td>{$equipe['ville']} {$equipe['nom_equipe']}</td>
                            <td>{$equipe['matches_joues']}</td>
                            <td>{$equipe['nombre_de_victoire']}</td>
                            <td>{$equipe['nombre_de_defaite']}</td>
                            <td>{$pourcentage}%</td>
                          </tr>";
                    $position++;
                }
                ?>
            </table>
        </div>

        <h2>Conférence Ouest</h2>
        <div class="table-container">
            <table>
                <tr>
                    <th>Position</th>
                    <th>Équipe</th>
                    <th>Matches joués</th>
                    <th>Victoires</th>
                    <th>Défaites</th>
                    <th>% Victoires</th>
                </tr>
                <?php
                $position = 1;
                foreach ($equipes_ouest as $equipe) {
                    $pourcentage = round(($equipe['nombre_de_victoire'] / $equipe['matches_joues']) * 100, 1);
                    echo "<tr>
                            <td>{$position}</td>
                            <td>{$equipe['ville']} {$equipe['nom_equipe']}</td>
                            <td>{$equipe['matches_joues']}</td>
                            <td>{$equipe['nombre_de_victoire']}</td>
                            <td>{$equipe['nombre_de_defaite']}</td>
                            <td>{$pourcentage}%</td>
                          </tr>";
                    $position++;
                }
                ?>
            </table>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 NBA Stats</p>
    </footer>
</body>
</html>

<?php
mysqli_close($conn);
?>
