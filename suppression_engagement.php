<?php

include "functions/db_functions.php";

$dbh = db_connect();

$code_ax = isset($_GET["code_ax"]) ? $_GET["code_ax"]:null;
$date_engagement = isset($_GET["date_engagement"]) ? $_GET["date_engagement"]:null;
if ($code_ax == null || $date_engagement == null) {
    die ("Erreur lors de la récupération de l'id dans l'url");
}
$delete = isset($_POST["delete"]);

if ($delete) {
    $params = array(
        ":code_ax" => $code_ax,
        ":date_engagement" => $date_engagement,
    );
    try {
        $sth = $dbh -> prepare("CALL delete_engagement(:code_ax, :date_engagement)");
        $sth -> execute($params);
    } catch (PDOException $ex) {
        die("Erreur lors de la suppression des données :". $ex -> getMessage());
    }
    header("Location: gestion_engagement.php");
    exit;
} else {

    $params = array(
        ":code_ax" => $code_ax,
        ":date_engagement" => $date_engagement,
    );
    try {
        $sth = $dbh -> prepare("CALL get_engagement_by_id(:code_ax, :date_engagement)");
        $sth -> execute($params);
        $suppresion_engagements = $sth -> fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
        die("Erreur lors de la récupération des données :". $ex -> getMessage());
    }
}

?>
 <!DOCTYPE html>
 <html lang="fr">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppression engagement</title>
    <link rel="stylesheet" href="css/main.css">
 </head>
 <body>
    <?php include "tete_page.php"; ?>
    
    <h1 style = "text-align: center; margin-top: 20px;">Suppression d'un engagement</h1>

    <hr style="border: 1px solid black; width: 100%;">
    
    <form action="" method="post" style = "text-align: center;">
        
        <p>Code AX : <br><input type="text" name="" value = "<?php  echo $code_ax ?>" disabled></p>

        <p>Code Movex :<br><input type="text" name="" value = "<?php echo $suppresion_engagements["code_movex"] ?>" disabled maxlength="20" minlength="3"></p>

        <p>Désignation produit : <br><input type="text" name="" value = "<?php echo $suppresion_engagements["designation_produit"] ?>" disabled maxlength="20" minlength="3"></p>

        <p>Référence commerciale : <br><input type="text" name="" value = "<?php echo $suppresion_engagements["reference_commerciale"] ?>" disabled maxlength="20" minlength="3"></p>

        <p>Date engagement :<br><input type="date" name="" value = "<?php echo $suppresion_engagements["date_engagement"] ?>" disabled maxlength="20" minlength="3"></p>

        <p>Quantité engagement :<br><input type="number" name="" value = "<?php echo $suppresion_engagements["qte_engagement"] ?>" disabled maxlength="20" minlength="3"></p>

        <input type="submit" name = "delete" value="Supprimer">
    </form>
 </body>
 </html>