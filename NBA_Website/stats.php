<?php

require_once 'connect.php';

$sql = "SELECT j.id_joueur, j.nom, j.age, j.Id_equipe, j.Passe, j.Points, j.faute, j.duel_gagne, j.Titulaire,
        e.nom_equipe, e.ville
        FROM joueurs j
        JOIN equipe e ON j.Id_equipe = e.id_equipe
        ORDER BY j.Points DESC";

$resultat = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques des Joueurs NBA</title>
    <link rel="stylesheet" href="stats.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Statistiques des Joueurs NBA</h1>
            <nav>
                <ul>
                    <li><a href="Match_user.php">Matches</a></li>
                    <li><a href="classement.php">Classement</a></li>
                    <li><a href="stats.php" class="active">Statistiques</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <h2>Meilleurs Joueurs</h2>
            <table>
                <thead>
                    <tr>
                        <th>Joueur</th>
                        <th>Équipe</th>
                        <th>Âge</th>
                        <th>Points</th>
                        <th>Passes</th>
                        <th>Duels gagnés</th>
                        <th>Fautes</th>
                        <th>Titulaire</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($resultat && mysqli_num_rows($resultat) > 0) {
                        while ($joueur = mysqli_fetch_assoc($resultat)) {
                            $equipe = $joueur['ville'] . ' ' . $joueur['nom_equipe'];
                            $titulaire = $joueur['Titulaire'] == 1 ? 'Oui' : 'Non';
                            
                            echo "<tr>
                                    <td>{$joueur['nom']}</td>
                                    <td>{$equipe}</td>
                                    <td>{$joueur['age']}</td>
                                    <td>{$joueur['Points']}</td>
                                    <td>{$joueur['Passe']}</td>
                                    <td>{$joueur['duel_gagne']}</td>
                                    <td>{$joueur['faute']}</td>
                                    <td>{$titulaire}</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>Aucun joueur trouvé</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </main>

        <footer>
            <p>&copy; 2025 NBA Stats</p>
        </footer>
    </div>
</body>
</html>
<?php
// Fermeture de la connexion
mysqli_close($conn);
?>