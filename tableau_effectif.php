<?php

//Récupération de ma fonction de connexion à ma BDD
include "functions/db_functions.php";

$dbh = db_connect();

//Récupération des utilisateurs
$sql = "CALL GetAllUsers()";
try {
    $sth = $dbh -> prepare($sql);
    $sth -> execute();
    $users = $sth -> fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    die ("Erreur lors de la récupération des informations des utilisateurs :". $ex -> getMessage());
}

$sql_prod = "SELECT "
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des effectifs</title>
    <style>
        table {
            width: 80%;
            border-collapse: collapse;
            margin: auto;
        }
        th {
            border: 1px solid #ddd;
            color: white;
            padding: 8px;
            text-align: left;
            background-color: #00ae4e;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2; /* Gris clair */
        }
        tr:nth-child(odd) {
            background-color: #ffffff; /* Blanc */
        }
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <!--Permet d'afficher la tête de page sur toutes les pages -->
    <?php include "tete_page.php"; ?>
    <h1 style = "text-align: center; margin-top: 20px;">Tableau des effectifs</h1>
    

    <table style = "margin-top: 20px;">
        <tr>
            <th>Unité Production</th>
            <th>Secteur</th>
            <th>Nom-Prénom Manager</th>
            <th>Nom d'utilisateur</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Fiche Emploi</th>
            <th>Type Contrat</th>
            <th>Type Equipe</th>
            <th>Statut</th>
            <th>Action</th>
        </tr>
            <?php
                foreach ($users as $user) {

                    $lien_update = '<a href="modification_effectif.php?id_utilisateur='.$user["id_utilisateur"].'">Modifier</a>';

                    echo "<tr>";
                    echo "<td>".$user["unite_production"]."</td>";
                    echo "<td>".$user["secteur"]."</td>";
                    echo "<td>".$user["nom_prenom_manager"]."</td>";
                    echo "<td>".$user["username"]."</td>";
                    echo "<td>".$user["nom"]."</td>";
                    echo "<td>".$user["prenom"]."</td>";
                    echo "<td>".$user["type_emploi"]."</td>";
                    echo "<td>".$user["type_contrat"]."</td>";
                    echo "<td>".$user["type_equipe"]."</td>";
                    echo "<td>".$user["statut"]."</td>";
                    echo "<td>".$lien_update."</td>";
                    echo "</tr>";
                }
            ?>
    </table>
    <?php
        $aujourdHui = new DateTime();
        $dates = [];
        for ($i = 0; $i < 15; $i++) {
            $jour = clone $aujourdHui;
            $jour->modify("+$i day");
            $dates[] = $jour->format('Y-m-d');
        }

        $sql_abs = "SELECT * FROM absence WHERE date_absence BETWEEN :start AND :end";
        $sth = $dbh->prepare($sql_abs);
        $sth->execute([
        ':start' => $dates[0],
        ':end' => end($dates)
        ]);
            $absences = $sth->fetchAll(PDO::FETCH_ASSOC);

        // Regrouper les absences par [id_utilisateur][date_absence] = lib_absence
        $absenceMap = [];
        foreach ($absences as $abs) {
            $absenceMap[$abs['id_utilisateur']][$abs['date_absence']] = true;
        }

        echo "<h2 style='text-align:center; margin-top: 40px;'>Planning des effectifs - 15 prochains jours</h2>";
        echo "<table style='margin-top: 20px;'>";

        // Ligne d'en-tête
        echo "<tr><th>Nom</th><th>Prénom</th>";
        foreach ($dates as $date) {
        echo "<th>" . date("d/m", strtotime($date)) . "</th>";
        }
        echo "</tr>";

        // Données utilisateurs
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>{$user['nom']}</td>";
            echo "<td>{$user['prenom']}</td>";
        foreach ($dates as $date) {
            if (isset($absenceMap[$user['id_utilisateur']][$date])) {
            $type = $absenceMap[$user['id_utilisateur']][$date];
            echo "<td style='background-color: #fdd;'>❌ {$type}</td>";
        } else {
            echo "<td style='background-color: #dfd;'>✅</td>";
        }
        }
        echo "</tr>";
}
    echo "</table>";

?>
<p style = 'text-align: right; margin-top: 10px; margin-right: 20px;'><a href="saisie_effectif.php">+ Ajouter un effectif</a></p>
</body>
</html>