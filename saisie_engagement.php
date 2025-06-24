<?php

//Récupération de ma fonction de connexion à ma BDD
include "functions/db_functions.php";

$dbh = db_connect();

$code_ax = isset($_GET["code_ax"]) ? $_GET["code_ax"]:null;
if ($code_ax == null) {
    die ("Erreur lors de la récupération de l'id dans l'url");
}

//Récupération des inputs du formulaire d'insertion 
$code_movex = isset($_POST["code_movex"]) ? $_POST["code_movex"]:null;
$designation_produit = isset($_POST["designation_produit"]) ? $_POST["designation_produit"]:null;
$reference_commerciale = isset($_POST["reference_commerciale"]) ? $_POST["reference_commerciale"]:null;
$date_engagement = isset($_POST["date_engagement"]) ? $_POST["date_engagement"]:null;
$qte_engagement = isset($_POST["qte_engagement"]) ? $_POST["qte_engagement"]:null;
$submit = isset($_POST["submit"]);
$annuler = isset($_POST["annuler"]);

//Si le formulaire est soumis alors on "INSERT" les données présentes dans les inputs dans notre table "engagement" 
if ($submit) {
    $sql = "INSERT INTO engagement (date_engagement, qte_engagement, code_ax) VALUES (:date_engagement, :qte_engagement, :code_ax)";
    $params = array(
        ":date_engagement" => $date_engagement,
        ":qte_engagement" => $qte_engagement,
        ":code_ax" => $code_ax,
    );
    try {
        
        $sth = $dbh -> prepare($sql);
        $sth -> execute($params);
    } catch (PDOException $ex) {
        die("Erreur lors de l'insertion des données dans la table 'engagement' :" . $ex -> getMessage());
    }
    header("Location: gestion_engagement.php");
    exit;
} else {
    $sql = "SELECT * FROM produit WHERE code_ax = :code_ax";
    $params = array(
        ":code_ax" => $code_ax,
    );
    try {
        $sth = $dbh -> prepare($sql);
        $sth -> execute($params);
        $produits = $sth ->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
        die("Erreur lors de la requête d'affichage". $ex -> getMessage());
    }
}

if ($annuler) {
    header("Location: gestion_engagement.php");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saisie engagement :</title>
</head>
<body>
    <!--Permet d'afficher la tête de page sur toutes les pages -->
    <?php include "tete_page.php"?>

    <h1 style = "text-align: center; margin-top: 20px;">Saisie engagement</h1>

    <hr style="border: 1px solid black; width: 100%;">
    
    <form action="" method="POST" class="container mt-5" style="max-width: 600px;">
    <div class="mb-3">
        <label class="form-label">Code AX :</label>
        <input type="text" name="code_ax" class="form-control" value="<?= htmlspecialchars($produits["code_ax"] ?? '') ?>" maxlength="20" minlength="3">
    </div>

    <div class="mb-3">
        <label class="form-label">Code Movex :</label>
        <input type="text" name="code_movex" class="form-control" value="<?= htmlspecialchars($produits["code_movex"] ?? '') ?>" maxlength="20" minlength="3">
    </div>

    <div class="mb-3">
        <label class="form-label">Désignation :</label>
        <input type="text" name="designation_produit" class="form-control" value="<?= htmlspecialchars($produits["designation_produit"] ?? '') ?>" maxlength="20" minlength="3">
    </div>

    <div class="mb-3">
        <label class="form-label">Référence commerciale :</label>
        <input type="text" name="reference_commerciale" class="form-control" value="<?= htmlspecialchars($produits["reference_commerciale"] ?? '') ?>" maxlength="20" minlength="3">
    </div>

    <div class="mb-3">
        <label class="form-label">Date de livraison :</label>
        <input type="date" name="date_engagement" class="form-control" maxlength="20" minlength="3">
    </div>

    <div class="mb-3">
        <label class="form-label">Quantité à livrer :</label>
        <input type="number" name="qte_engagement" class="form-control" maxlength="20" minlength="3">
    </div>

    <div class="d-flex justify-content-center gap-2">
        <button type="submit" name="submit" class="btn btn-success">Enregistrer</button>
        <button type="submit" name="annuler" class="btn btn-secondary">Annuler</button>
    </div>
</form>

</body>
</html>