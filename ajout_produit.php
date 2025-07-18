<?php

//Récupération de ma fonction de connexion à ma BDD
include "functions/db_functions.php";

$dbh = db_connect();

//Récupération des input du formulaire
$code_ax = isset($_POST["code_ax"]) ? $_POST["code_ax"]:null;
$code_movex = isset($_POST["code_movex"]) ? $_POST["code_movex"]:null;
$designation_produit = isset($_POST["designation_produit"]) ? $_POST["designation_produit"]:null;
$reference_commerciale = isset($_POST["reference_commerciale"]) ? $_POST["reference_commerciale"]:null;
$submit = isset($_POST["submit"]);
$annuler = isset($_POST["annuler"]);

//Si le formulaire est soumis alors on "INSERT" les input dans la table "produit"
if ($submit) {
    $sql = "CALL InsertProduits(:code_ax, :code_movex, :designation_produit, :reference_commerciale)";
    $params = array(
        ":code_ax" => $code_ax,
        ":code_movex" => $code_movex,
        ":designation_produit" => $designation_produit,
        ":reference_commerciale" => $reference_commerciale,
    );
    try {

        $sth = $dbh -> prepare($sql);
        $sth -> execute($params);

    } catch (PDOException $ex) {
        die("Erreur lors de l'insertion des données dans la table 'produit' : " . $ex -> getMessage());
    }
    header("Location: gestion_produit.php");
    exit;
}
//Si on annule la saisie, on est redirigé vers la page "Gestion des produits"
if ($annuler) {
    header("Location: gestion_produit.php");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout d'un produit</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <?php include "tete_page.php";?>

    <h1 style = "text-align: center; margin-top: 20px;"> Ajout d'un produit</h1>

    <hr style="border: 1px solid black; width: 100%;">
    
    <form action="" method="POST" class="container mt-5" style="max-width: 500px;">
    <div class="mb-3">
        <label class="form-label">Code AX :</label>
        <input type="text" name="code_ax" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Code Movex :</label>
        <input type="text" name="code_movex" class="form-control" maxlength="20" minlength="3">
    </div>

    <div class="mb-3">
        <label class="form-label">Désignation :</label>
        <input type="text" name="designation_produit" class="form-control" maxlength="20" minlength="3">
    </div>

    <div class="mb-4">
        <label class="form-label">Référence commerciale :</label>
        <input type="text" name="reference_commerciale" class="form-control" maxlength="20" minlength="3">
    </div>

    <div class="d-flex justify-content-between">
        <button type="submit" name="submit" class="btn btn-success">Enregistrer</button>
        <button type="submit" name="annuler" class="btn btn-secondary">Annuler</button>
    </div>
</form>

</body>
</html>