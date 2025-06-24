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
    $sql = "SELECT * FROM produit INNER JOIN engagement ON produit.code_ax = engagement.code_ax WHERE produit.code_ax = :code_ax ORDER BY date_engagement ASC";
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
            <th>Actions</th>
        </tr>
    
<?php
    foreach ($gestion_engagements as $gestion_engagement) {
        echo "<tr>";
        echo "<td>".htmlspecialchars($gestion_engagement["code_ax"])."</td>";
        echo "<td>".htmlspecialchars($gestion_engagement["code_movex"])."</td>";
        echo "<td>".htmlspecialchars($gestion_engagement["designation_produit"])."</td>";
        echo "<td>".htmlspecialchars($gestion_engagement["reference_commerciale"])."</td>";
        echo "<td>".htmlspecialchars($gestion_engagement["date_engagement"])."</td>";
        echo "<td>".htmlspecialchars($gestion_engagement["qte_engagement"])."</td>";
        echo "<td>
    <a href='modification_engagement.php?code_ax=" . urlencode($gestion_engagement["code_ax"]) . 
    "&date_engagement=" . urlencode($gestion_engagement["date_engagement"]) . "'>
        <button>
            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pen-fill' viewBox='0 0 16 16'>
                <path d='m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001'/>
            </svg>
        </button>
    </a>
    <a href='suppression_engagement.php?code_ax=" . urlencode($gestion_engagement["code_ax"]) . 
    "&date_engagement=" . urlencode($gestion_engagement["date_engagement"]) . "'>
        <button>
            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash-fill' viewBox='0 0 16 16'>
                <path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0'/>
            </svg>
        </button>
    </a>
</td>";

    }
?>    
    </table>
<?php
    echo '<a href="saisie_engagement.php?code_ax='.$search_code_ax.'"> + Saisir un engagement</a>';
}
?>
</body>
</html>