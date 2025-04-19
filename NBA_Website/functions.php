<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function connectDatabase() {
    $host = "localhost";
    $user = "root";
    $passwd = "";
    $bdd = "playoff_try";
    $conn = mysqli_connect($host, $user, $passwd, $bdd);

    if (!$conn) {
        die("<p style='color:red;'>Erreur de connexion: " . mysqli_connect_error() . "</p>");
    }
    return $conn;
}

function checkAndCreateMatches($conn, $match_threshold, $callback_function) {
    $query_count = "SELECT COUNT(*) as total_matches FROM matches";
    $result_count = mysqli_query($conn, $query_count);
    $row_count = mysqli_fetch_assoc($result_count);
    $total_matches = $row_count['total_matches'];

    if ($total_matches < $match_threshold) {
        call_user_func($callback_function);
    }
}

function updateMatch($conn, $match_id, $new_date, $id_arbitre, $score_equipe1, $score_equipe2, $winner, $quart_scores) {
    if (!$winner) {
        //Si pas de vainqueur choisis on update juste la date et l'arbitre
        $query = "UPDATE matches 
                  SET Date_match = ?, id_arbitre = ? 
                  WHERE id_match = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sii", $new_date, $id_arbitre, $match_id);

        if (!mysqli_stmt_execute($stmt)) {
            echo "<p style='color:red;'>Erreur lors de la mise à jour de la date: " . mysqli_error($conn) . "</p>";
        } else {
            echo "<p style='color:green;'>Date mise à jour avec succès !</p>";
        }

        mysqli_stmt_close($stmt);
        return; // Exit the function since no winner is selected
    }

    // Si ya un vainqueur, on update le match et les stats
    update_Location($conn, $match_id);

    $column = ($winner == 'Equipe1') ? 'Equipe1_victorieuse' : 'Equipe2_victorieuse';

    $query = "UPDATE matches 
              SET $column = CASE WHEN $column < 4 THEN $column + 1 ELSE $column END, 
                  Date_match = ?, id_arbitre = ?, id_round = id_round + 1
              WHERE id_match = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sii", $new_date, $id_arbitre, $match_id);

    if (!mysqli_stmt_execute($stmt)) {
        echo "<p style='color:red;'>Erreur lors de la mise à jour du match: " . mysqli_error($conn) . "</p>";
    } else {
        $query_idround = "SELECT id_round FROM matches WHERE id_match = $match_id";  
        $result = mysqli_query($conn, $query_idround);
        
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $id_round = intval($row['id_round']); // sa recup l'id du round
        } else {
            die("Query failed: " . mysqli_error($conn)); // Du debugging
        }
        
        updateMatchStats($conn, $match_id, $score_equipe1, $score_equipe2, $id_round, $quart_scores);
    }

    mysqli_stmt_close($stmt);
}

function updateMatchStats($conn, $match_id, $score_equipe1, $score_equipe2, $id_round, $quart_scores) {
    // sa recup les valeurs des scores des quart temps

    $q1_e1 = $quart_scores['q1_e1'];
    $q1_e2 = $quart_scores['q1_e2'];
    $q2_e1 = $quart_scores['q2_e1'];
    $q2_e2 = $quart_scores['q2_e2'];
    $q3_e1 = $quart_scores['q3_e1'];
    $q3_e2 = $quart_scores['q3_e2'];
    $q4_e1 = $quart_scores['q4_e1'];
    $q4_e2 = $quart_scores['q4_e2'];
    // sa recup la location et la phase du match
    $query_get_Location_match = "SELECT Location_match FROM matches WHERE id_match = ?";
    $stmt_get_Location_match = mysqli_prepare($conn, $query_get_Location_match);
    mysqli_stmt_bind_param($stmt_get_Location_match, "i", $match_id);
    mysqli_stmt_execute($stmt_get_Location_match);
    $result_Location_match = mysqli_stmt_get_result($stmt_get_Location_match);
    $row_Location_match = mysqli_fetch_assoc($result_Location_match);
    $Location_match = $row_Location_match['Location_match'];
    mysqli_stmt_close($stmt_get_Location_match);

    $query_get_phase = "SELECT phase FROM matches WHERE id_match = ?";
    $stmt_get_phase = mysqli_prepare($conn, $query_get_phase);
    mysqli_stmt_bind_param($stmt_get_phase, "i", $match_id);
    mysqli_stmt_execute($stmt_get_phase);
    $result_phase = mysqli_stmt_get_result($stmt_get_phase);
    $row_phase = mysqli_fetch_assoc($result_phase);
    $phase = $row_phase['phase'];
    mysqli_stmt_close($stmt_get_phase);

    $query_count = "SELECT COUNT(*) as count FROM matchs_stats WHERE id_match = ?";
    $stmt_count = mysqli_prepare($conn, $query_count);
    mysqli_stmt_bind_param($stmt_count, "i", $match_id);
    mysqli_stmt_execute($stmt_count);
    $result_count = mysqli_stmt_get_result($stmt_count);
    $row_count = mysqli_fetch_assoc($result_count);
    $total_entries = $row_count['count'];
    mysqli_stmt_close($stmt_count);

    if ($total_entries < 7) {
        $query_insert = "INSERT INTO matchs_stats (
            id_match, saison, score_equipe1, score_equipe2, 
            score_quart_temps1_equipe1, score_quart_temps1_equipe2, 
            score_quart_temps2_equipe1, score_quart_temps2_equipe2, 
            score_quart_temps3_equipe1, score_quart_temps3_equipe2, 
            score_quart_temps4_equipe1, score_quart_temps4_equipe2, 
            id_round, Location_match, phase
        ) VALUES (?, YEAR(NOW()), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = mysqli_prepare($conn, $query_insert);
        mysqli_stmt_bind_param(
            $stmt_insert, "iiiiiiiiiiiisi", 
            $match_id, $score_equipe1, $score_equipe2, 
            $q1_e1, $q1_e2, $q2_e1, $q2_e2, 
            $q3_e1, $q3_e2, $q4_e1, $q4_e2, 
            $id_round, $Location_match, $phase
        );
        mysqli_stmt_execute($stmt_insert);
        mysqli_stmt_close($stmt_insert);
    } else {
        $query_update = "UPDATE matchs_stats SET 
            score_equipe1 = ?, score_equipe2 = ?, 
            score_quart_temps1_equipe1 = ?, score_quart_temps1_equipe2 = ?, 
            score_quart_temps2_equipe1 = ?, score_quart_temps2_equipe2 = ?, 
            score_quart_temps3_equipe1 = ?, score_quart_temps3_equipe2 = ?, 
            score_quart_temps4_equipe1 = ?, score_quart_temps4_equipe2 = ? 
            WHERE id = (SELECT id FROM matchs_stats WHERE id_match = ? ORDER BY id DESC LIMIT 1)";
        $stmt_update = mysqli_prepare($conn, $query_update);
        mysqli_stmt_bind_param(
            $stmt_update, "iiiiiiiiiii", 
            $score_equipe1, $score_equipe2, 
            $q1_e1, $q1_e2, $q2_e1, $q2_e2, 
            $q3_e1, $q3_e2, $q4_e1, $q4_e2, 
            $match_id
        );
        mysqli_stmt_execute($stmt_update);
        mysqli_stmt_close($stmt_update);
    }
}

function getMatches($conn, $phase) {
    //query pour recup les matchs en fonction de la phase
    $query = "
        SELECT m.id_match, m.Date_match, m.phase,
               e1.nom_equipe AS Equipe1, 
               e2.nom_equipe AS Equipe2, 
               m.Equipe1_victorieuse, 
               m.Equipe2_victorieuse, 
               m.id_arbitre
        FROM matches m
        JOIN equipe e1 ON m.Equipe1 = e1.id_equipe
        JOIN equipe e2 ON m.Equipe2 = e2.id_equipe
       
          WHERE m.phase = ?
        ORDER BY m.Date_match DESC";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $phase);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}


function displayMatches($result, $conn) {
    echo "<table border='1' cellspacing='0' cellpadding='10'>";
    echo "<tr>
            <th>Match</th>
            <th>Date</th>
            <th>Score</th>
            <th>Quart Temps</th>
            <th>Gagnant</th>
            <th>Arbitre</th>
            <th>Victoires</th> <!-- New column for wins -->
            <th>Action</th>
            <th>Voir les Stats</th> 
          </tr>";

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td><strong>{$row['Equipe1']} vs {$row['Equipe2']}</strong></td>";

            echo "<form method='POST' action=''>";
            echo "<td><input type='date' name='Date_match' value='{$row['Date_match']}'></td>";

            echo "<td>
                    <input type='number' name='score_equipe1' value='0' min='0' required> - 
                    <input type='number' name='score_equipe2' value='0' min='0' required>
                  </td>";
            //Input pour les scores des quart temps
            echo "<td>
                    Q1: <input type='number' name='q1_e1' value='0' min='0' required> - 
                        <input type='number' name='q1_e2' value='0' min='0' required><br>
                    Q2: <input type='number' name='q2_e1' value='0' min='0' required> - 
                        <input type='number' name='q2_e2' value='0' min='0' required><br>
                    Q3: <input type='number' name='q3_e1' value='0' min='0' required> - 
                        <input type='number' name='q3_e2' value='0' min='0' required><br>
                    Q4: <input type='number' name='q4_e1' value='0' min='0' required> - 
                        <input type='number' name='q4_e2' value='0' min='0' required>
                  </td>";
            //Input pour le gagnant
            echo "<td>
                    <input type='radio' name='winner' value='Equipe1'> {$row['Equipe1']}
                    <input type='radio' name='winner' value='Equipe2'> {$row['Equipe2']}
                  </td>";

            echo "<td><select name='id_arbitre'><option value=''>Aucun arbitre</option>";

            $arbitres = mysqli_query($conn, "SELECT id_arbitre, nom FROM arbitres");
            while ($arbitre = mysqli_fetch_assoc($arbitres)) {
                echo "<option value='{$arbitre['id_arbitre']}'>{$arbitre['nom']}</option>";
            }
            echo "</select></td>";

            echo "<td>{$row['Equipe1_victorieuse']} - {$row['Equipe2_victorieuse']}</td>";

            echo "<input type='hidden' name='match_id' value='{$row['id_match']}'>";
            echo "<td><button type='submit' name='update_match'>Mettre à jour</button></td>";
            echo "<td><a href='Modifier_stats_joueurs.php?id_match={$row['id_match']}&Equipe1={$row['Equipe1']}&Equipe2={$row['Equipe2']}'>Voir les Stats</a></td>";
            echo "</form>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='9'>Aucun match trouvé</td></tr>";
    }

    echo "</table>";
}

function getMatches_user_huitieme($conn) {
    $query = "
        SELECT m.id_match, m.Date_match, phase,
               e1.nom_equipe AS Equipe1, 
               e2.nom_equipe AS Equipe2, 
               m.Equipe1_victorieuse, 
               m.Equipe2_victorieuse
        FROM matches m
        JOIN equipe e1 ON m.Equipe1 = e1.id_equipe
        JOIN equipe e2 ON m.Equipe2 = e2.id_equipe
        WHERE phase=1
        ORDER BY m.Date_match ASC";
        
    return mysqli_query($conn, $query);
}
function getMatches_user_quart($conn) {
    $query = "
        SELECT m.id_match, m.Date_match, phase,
               e1.nom_equipe AS Equipe1, 
               e2.nom_equipe AS Equipe2, 
               m.Equipe1_victorieuse, 
               m.Equipe2_victorieuse
        FROM matches m
        JOIN equipe e1 ON m.Equipe1 = e1.id_equipe
        JOIN equipe e2 ON m.Equipe2 = e2.id_equipe
        WHERE phase=2
        ORDER BY m.Date_match ASC";
        
    return mysqli_query($conn, $query);
}
function getMatches_user_demi($conn) {
    $query = "
        SELECT m.id_match, m.Date_match, phase,
               e1.nom_equipe AS Equipe1, 
               e2.nom_equipe AS Equipe2, 
               m.Equipe1_victorieuse, 
               m.Equipe2_victorieuse
        FROM matches m
        JOIN equipe e1 ON m.Equipe1 = e1.id_equipe
        JOIN equipe e2 ON m.Equipe2 = e2.id_equipe
        WHERE phase=3
        ORDER BY m.Date_match ASC";
        
    return mysqli_query($conn, $query);
}
function getMatches_user_finale($conn) {
    $query = "
        SELECT m.id_match, m.Date_match, phase,
               e1.nom_equipe AS Equipe1, 
               e2.nom_equipe AS Equipe2, 
               m.Equipe1_victorieuse, 
               m.Equipe2_victorieuse
        FROM matches m
        JOIN equipe e1 ON m.Equipe1 = e1.id_equipe
        JOIN equipe e2 ON m.Equipe2 = e2.id_equipe
        WHERE phase=4
        ORDER BY m.Date_match ASC";
        
    return mysqli_query($conn, $query);
}

function renderMatchTable_user($conn, $result) {
    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%; text-align: center;'>";
        echo "<tr>
            <th>Match</th>
            <th>Date</th>
            <th>Score</th>
          </tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$row['Equipe1']} vs {$row['Equipe2']}</td>";
            echo "<td>{$row['Date_match']}</td>";
            echo "<td>{$row['Equipe1_victorieuse']} - {$row['Equipe2_victorieuse']}</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>Aucun match trouvé</p>";
    }
}
function renderMatches_user($conn, $result) {

    if (!$result || mysqli_num_rows($result) == 0) {
        echo "<p>Aucun match trouvé pour cette phase.</p>";
        return;
    }

    echo '<table border="1" cellspacing="0" cellpadding="8">';
    echo '<tr>
            <th>ID Match</th>
            <th>Date</th>
            <th>Équipe 1</th>
            <th>Équipe 2</th>
            <th>Victoires Équipe 1</th>
            <th>Victoires Équipe 2</th>
            <th>Détails</th>
          </tr>';

    while ($match = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($match['id_match']) . '</td>';
        echo '<td>' . htmlspecialchars($match['Date_match']) . '</td>';
        echo '<td>' . htmlspecialchars($match['Equipe1']) . '</td>';
        echo '<td>' . htmlspecialchars($match['Equipe2']) . '</td>';
        echo '<td>' . htmlspecialchars($match['Equipe1_victorieuse']) . '</td>';
        echo '<td>' . htmlspecialchars($match['Equipe2_victorieuse']) . '</td>';
        echo '<td><a href="stats_detaille_match.php?id_match=' . htmlspecialchars($match['id_match']) . '">Voir les Stats</a></td>';
        echo '</tr>';
    }

    echo '</table>';
}

function update_Location($conn, $match_id) {
    // Get the current round for the match
    $query = "SELECT id_round, Equipe1, Equipe2 FROM matches WHERE id_match = ?";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        echo "<p style='color:red;'>Erreur lors de la préparation de la requête: " . mysqli_error($conn) . "</p>";
        return;
    }
    mysqli_stmt_bind_param($stmt, "i", $match_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $match = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($match) {
        $id_round = intval($match['id_round']);
        $equipe1_id = $match['Equipe1'];
        $equipe2_id = $match['Equipe2'];

        // Determine la location baser sur le id_round
        if ($id_round >= 0 && $id_round < 2) {
            // Get the ville of Equipe1 et 2
            $query_ville = "SELECT ville FROM equipe WHERE id_equipe = ?";
            $stmt_ville = mysqli_prepare($conn, $query_ville);
            if (!$stmt_ville) {
                echo "<p style='color:red;'>Erreur lors de la préparation de la requête: " . mysqli_error($conn) . "</p>";
                return;
            }
            mysqli_stmt_bind_param($stmt_ville, "i", $equipe1_id);
        } elseif ($id_round >= 2 && $id_round <= 4) {
            $query_ville = "SELECT ville FROM equipe WHERE id_equipe = ?";
            $stmt_ville = mysqli_prepare($conn, $query_ville);
            if (!$stmt_ville) {
                echo "<p style='color:red;'>Erreur lors de la préparation de la requête: " . mysqli_error($conn) . "</p>";
                return;
            }
            mysqli_stmt_bind_param($stmt_ville, "i", $equipe2_id);
        } else {
            return;
        }

        mysqli_stmt_execute($stmt_ville);
        $result_ville = mysqli_stmt_get_result($stmt_ville);
        $ville = mysqli_fetch_assoc($result_ville)['ville'];
        mysqli_stmt_close($stmt_ville);

        // Sa va update la loctaion du match
        $query_update = "UPDATE matches SET Location_match = ? WHERE id_match = ?";
        $stmt_update = mysqli_prepare($conn, $query_update);
        if (!$stmt_update) {
            echo "<p style='color:red;'>Erreur lors de la préparation de la requête: " . mysqli_error($conn) . "</p>";
            return;
        }
        mysqli_stmt_bind_param($stmt_update, "si", $ville, $match_id);
        if (mysqli_stmt_execute($stmt_update)) {
        } else {
            echo "<p style='color:red;'>Erreur lors de la mise à jour de la location: " . mysqli_error($conn) . "</p>";
        }
        mysqli_stmt_close($stmt_update);
    } else {
        echo "<p style='color:red;'>Match non trouvé.</p>";
    }
}

?>
