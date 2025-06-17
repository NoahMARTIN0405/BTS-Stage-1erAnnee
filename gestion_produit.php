<?php

//Récupération de ma fonction de connexion à ma BDD
include "functions/db_functions.php"; 

$dbh = db_connect();

//Récupération des produits
try {
    $sth = $dbh->prepare("CALL lister_produits()");
    $sth -> execute();
    $produits = $sth -> fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    die ("Erreur lors de la récupération des produits : " . $ex -> getMessage());
}
$lien_insert= '<a href="ajout_produit.php">+ Ajouter un produit</a>';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des produits</title>
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
    <?php include "tete_page.php";?>
    <h1 style = "text-align: center; margin-top: 20px;">Gestion des produits</h1>
    <table style = "margin-top: 20px;">
        <tr>
            <th>Code AX</th>
            <th>Code Movex</th>
            <th>Désignation</th>
            <th>Référence commerciale</th>
            <th>Actions</th>
        </tr>
        <?php
            foreach ($produits as $produit) {
                
                $lien_update = '<a href="modification_produit.php?code_ax='.$produit["code_ax"].'">Modifier</a>';
                echo "<tr class = 'table-active'>";
                echo "<td>".$produit["code_ax"]."</td>";
                echo "<td>".$produit["code_movex"]."</td>";
                echo "<td>".$produit["designation_produit"]."</td>";
                echo "<td>".$produit["reference_commerciale"]."</td>";
                echo "<td>".$lien_update."</td>";
                echo "</tr>";
            }   
              
            
        ?>
    </table>
        <?php 
        if ($_SESSION["id_usertype"] == 3) {
        echo "<p style = 'text-align: right; margin-top: 10px; margin-right: 20px;'>".$lien_insert."</p>"; 
        
        }
        ?>
</body>
</html>