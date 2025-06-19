<?php

//Récupération de ma fonction de connexion à ma BDD
include "functions/db_functions.php"; 

$dbh = db_connect();

//Récupération des produits
$sql = "SELECT * FROM produit";
try {
    $sth = $dbh->prepare($sql);
    $sth -> execute();
    $produits = $sth -> fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    die ("Erreur lors de la récupération des produits : " . $ex -> getMessage());
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des stocks</title>
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
    <?php include "tete_page.php"?>
    <h1 style = "text-align: center; margin-top: 20px;">Gestion des stocks</h1>


    <table style = "margin-top: 20px;">
        <tr>
            <th>Code AX</th>
            <th>Code Movex</th>
            <th>Désignation</th>
            <th>Référence commerciale</th>
            <th>Stock sécurité attendu</th>
            <th>Stock sécurité réel</th>
            <th>Commentaires</th>
            <th>Actions</th>
        </tr>
        <?php
            //Affichage des produits
            foreach ($produits as $produit) {
                
                $lien_update = '<a href="modification_stock.php?code_ax='.$produit["code_ax"].'">Modifier</a>';
                echo "<tr>";
                echo "<td>".$produit["code_ax"]."</td>";
                echo "<td>".$produit["code_movex"]."</td>";
                echo "<td>".$produit["designation_produit"]."</td>";
                echo "<td>".$produit["reference_commerciale"]."</td>";
                echo "<td>".$produit["stock_secu_attendu"]."</td>";
                echo "<td>".$produit["stock_secu_reel"]."</td>";
                echo "<td>".$produit["commentaire_stock"]."</td>";
                echo "<td>".$lien_update."</td>";
                echo "</tr>";

            }      
            
        ?>
    </table>
</body>
</html>