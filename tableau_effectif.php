<?php

//Récupération de ma fonction de connexion à ma BDD
include "functions/db_functions.php";

$dbh = db_connect();

//Récupération des utilisateurs
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
    <title>Gestion des effectifs</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
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