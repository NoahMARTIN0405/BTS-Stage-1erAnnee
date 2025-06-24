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
    $sql = "CALL delete_engagement(:code_ax, :date_engagement)";
    $params = array(
        ":code_ax" => $code_ax,
        ":date_engagement" => $date_engagement,
    );
    try {
        $sth = $dbh -> prepare($sql);
        $sth -> execute($params);
    } catch (PDOException $ex) {
        die("Erreur lors de la suppression des données :". $ex -> getMessage());
    }
    header("Location: gestion_engagement.php");
    exit;
} else {
    $sql = "CALL get_engagement_produit(:code_ax, :date_engagement)";
    $params = array(
        ":code_ax" => $code_ax,
        ":date_engagement" => $date_engagement,
    );
    try {
        $sth = $dbh -> prepare($sql);
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
    
    <form action="" method="post" class="container mt-5" style="max-width: 600px;">
    <div class="mb-3">
        <label class="form-label">Code AX :</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($code_ax) ?>" disabled>
    </div>

    <div class="mb-3">
        <label class="form-label">Code Movex :</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($suppresion_engagements["code_movex"]) ?>" disabled>
    </div>

    <div class="mb-3">
        <label class="form-label">Désignation produit :</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($suppresion_engagements["designation_produit"]) ?>" disabled>
    </div>

    <div class="mb-3">
        <label class="form-label">Référence commerciale :</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($suppresion_engagements["reference_commerciale"]) ?>" disabled>
    </div>

    <div class="mb-3">
        <label class="form-label">Date engagement :</label>
        <input type="date" class="form-control" value="<?= htmlspecialchars($suppresion_engagements["date_engagement"]) ?>" disabled>
    </div>

    <div class="mb-3">
        <label class="form-label">Quantité engagement :</label>
        <input type="number" class="form-control" value="<?= htmlspecialchars($suppresion_engagements["qte_engagement"]) ?>" disabled>
    </div>

    <div class="text-center">
        <button type="submit" name="delete" class="btn btn-danger">Supprimer</button>
    </div>
</form>

 </body>
 </html>