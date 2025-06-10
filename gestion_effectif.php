<?php

include "functions/db_functions.php";

$dbh = db_connect();

$sql = "SELECT * FROM utilisateur";
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
    <link rel="stylesheet" href="css/style.css">
    <title>Gestion des effectifs</title>
</head>
<body>
    <?php include "tete_page.php"; ?>
    <h1>Gestion des effectifs</h1>
    <table>
        <tr>
            <th>Unité Production</th>
            <th>Secteur</th>
            <th>Nom-Prénom Manager</th>
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
</body>
</html>