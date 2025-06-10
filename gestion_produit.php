<?php

include "functions/db_functions.php"; 

$dbh = db_connect();

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
    <link rel="stylesheet" href="css/style.css">
    <title>Gestion des produits</title>
</head>
<body>
    <?php include "tete_page.php";?>
    <h1>Gestion des produits :</h1>
    <table>
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
                $lien_insert= '<a href="ajout_produit.php">+ Ajouter un produit</a>';
                echo "<tr>";
                echo "<td>".$produit["code_ax"]."</td>";
                echo "<td>".$produit["code_movex"]."</td>";
                echo "<td>".$produit["designation_produit"]."</td>";
                echo "<td>".$produit["reference_commerciale"]."</td>";
                echo "<td>".$lien_update."</td>";
                echo "</tr>";
            }   
              
            
        ?>
    </table>
        <?php echo "<p>".$lien_insert."</p>"; ?>
</body>
</html>