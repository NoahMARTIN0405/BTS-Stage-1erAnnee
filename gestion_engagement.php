<?php
include "functions/db_functions.php";

$dbh = db_connect();

$search_code_ax = isset($_POST["search_code_ax"]) ? $_POST["search_code_ax"]: null;
$search = isset($_POST["search"]);

?> 
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
    <?php include "tete_page.php"; ?>
    
    <h1 style = "text-align: center; margin-top: 20px;">Gestion des engagements</h1>

    <hr style="border: 1px solid black; width: 100%;">
    
    <h2 style = "margin-left: 20px; margin-top: 20px;">Rechercher des engagements :</h2>

    <form action="" method = "POST">
        <input type = "text" name = "search_code_ax" placeholder = "Recherchez votre code AX" style = "width: 250px; margin-left: 20px;">
        <button type = "submit" name = "search"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
        </svg></button>
    </form>
    <br>
<?php        
    if ($search) {
    $sql = "SELECT * FROM produit INNER JOIN engagement ON produit.code_ax = engagement.code_ax WHERE produit.code_ax = :code_ax";
    $params = array(
        ":code_ax" => $search_code_ax,
    );
    try {
        $sth = $dbh -> prepare($sql);
        $sth -> execute($params);
        $gestion_engagements = $sth -> fetcHAll(PDO::FETCH_ASSOC);
    } catch (PDOException $ex){
        die("Erreur lors de la recherche des code_ax". $ex ->getMessage());
    }
?>
    <table>
        <tr>
            <th>Code AX</th>
            <th>Code MOVEX</th>
            <th>Désignation produit</th>
            <th>Référence commerciale</th>
            <th>Date engagement</th>
            <th>Quantité engagement</th>
        </tr>
    
<?php
    foreach ($gestion_engagements as $gestion_engagement) {
        $lien_saisie = '<a href="saisie_engagement.php?code_ax='.$gestion_engagement["code_ax"].'"> + Saisir un engagement</a>';
        echo "<tr>";
        echo "<td>".htmlspecialchars($gestion_engagement["code_ax"])."</td>";
        echo "<td>".htmlspecialchars($gestion_engagement["code_movex"])."</td>";
        echo "<td>".htmlspecialchars($gestion_engagement["designation_produit"])."</td>";
        echo "<td>".htmlspecialchars($gestion_engagement["reference_commerciale"])."</td>";
        echo "<td>".htmlspecialchars($gestion_engagement["date_engagement"])."</td>";
        echo "<td>".htmlspecialchars($gestion_engagement["qte_engagement"])."</td>";
        echo "</tr>";
    }
?>    
    </table>
<?php
    echo "<p style = 'text-align: right; margin-right: 20px;'>".$lien_saisie."</p>";
}
?>
</body>
</html>