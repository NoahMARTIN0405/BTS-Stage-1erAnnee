<?php

include "functions/db_functions.php";

$dbh = db_connect();

$code_ax = isset($_GET["code_ax"]) ? $_GET["code_ax"] : null;
$original_date_engagement = isset($_GET["date_engagement"]) ? $_GET["date_engagement"] : null;

if ($code_ax == null || $original_date_engagement == null) {
    die ("Erreur lors de la récupération des paramètres dans l'URL");
}

$new_date_engagement = isset($_POST["date_engagement"]) ? $_POST["date_engagement"] : null;
$qte_engagement = isset($_POST["qte_engagement"]) ? $_POST["qte_engagement"] : null;
$submit = isset($_POST["modification_date_engagement"]);

if ($submit) {
    $sql = "CALL update_engagement_by_code_and_date(
        :code_ax, :original_date_engagement, :new_date_engagement, :qte_engagement
    )";
    $params = array(
        ":code_ax" => $code_ax,
        ":new_date_engagement" => $new_date_engagement,
        ":qte_engagement" => $qte_engagement,
        ":original_date_engagement" => $original_date_engagement,
    ); 
    try {
        $sth = $dbh->prepare($sql);
        $sth->execute($params);
    } catch (PDOException $ex) {
        die("Erreur lors de la modification des données : " . $ex->getMessage());
    }
    header("Location: gestion_engagement.php");
    exit;
} else {
    $sql = "CALL get_produit_engagement_by_code_and_date(:code_ax, :date_engagement)";

    $params = array(
        ":code_ax" => $code_ax,
        ":date_engagement" => $original_date_engagement,
    );
    try {
        $sth = $dbh->prepare($sql);
        $sth->execute($params);
        $modification_engagements = $sth->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
        die("Erreur lors de la récupération des données : " . $ex->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <title>Document</title>
</head>
<body>
    <?php include "tete_page.php"; ?>

    <h1 style = "text-align: center; margin-top: 20px;">Modification d'un engagement</h1>

    <hr style="border: 1px solid black; width: 100%;">
    <form action="" method="post" class="container mt-5" style="max-width: 600px;">
    <div class="mb-3">
        <label class="form-label">Code AX :</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($code_ax); ?>" disabled maxlength="20" minlength="3">
    </div>

    <div class="mb-3">
        <label class="form-label">Code Movex :</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($modification_engagements["code_movex"]); ?>" disabled maxlength="20" minlength="3">
    </div>

    <div class="mb-3">
        <label class="form-label">Désignation produit :</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($modification_engagements["designation_produit"]); ?>" disabled maxlength="20" minlength="3">
    </div>

    <div class="mb-3">
        <label class="form-label">Référence commerciale :</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($modification_engagements["reference_commerciale"]); ?>" disabled maxlength="30" minlength="3">
    </div>

    <div class="mb-3">
        <label class="form-label">Date engagement :</label>
        <input type="date" name="date_engagement" class="form-control" value="<?= htmlspecialchars($modification_engagements["date_engagement"]); ?>">
    </div>

    <div class="mb-4">
        <label class="form-label">Quantité engagement :</label>
        <input type="number" name="qte_engagement" class="form-control" value="<?= htmlspecialchars($modification_engagements["qte_engagement"]); ?>">
    </div>

    <div class="text-center">
        <button type="submit" name="modification_date_engagement" class="btn btn-primary">Modifier</button>
    </div>
</form>


</body>
</html>
