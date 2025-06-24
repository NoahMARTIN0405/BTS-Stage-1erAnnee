<?php

//Récupération de ma fonction de connexion à ma BDD
include "functions/db_functions.php";

$dbh = db_connect();

//Récupération du code AX dans l'URL 
$code_ax = isset($_GET["code_ax"]) ? $_GET["code_ax"]:null;
if ($code_ax == null) {
    die ("Erreur lors de la récupération de l'id dans l'url");
}

//Récupération des input de mon formulaire
$code_movex = isset($_POST["code_movex"]) ? $_POST["code_movex"]:null;
$designation_produit = isset($_POST["designation_produit"]) ? $_POST["designation_produit"]:null;
$reference_commerciale = isset($_POST["reference_commerciale"]) ? $_POST["reference_commerciale"]:null;
$submit = isset($_POST["submit"]);
$annuler = isset($_POST["annuler"]);

//Si mon formulaire est soumis, on "UPDATE" les données que l'on avait dans la table produit et on les remplace par les données présente dans les input
if ($submit) {
    $sql = "UPDATE produit SET code_movex = :code_movex, designation_produit = :designation_produit, reference_commerciale = :reference_commerciale WHERE code_ax =:code_ax";
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
        die("Erreur lors de la modification des données :". $ex -> getMessage());
    }
    header("Location: gestion_produit.php");
    exit;
    
    //Sinon on affiche seulement les données déjà connu dans nos inputs
} else {
    $sql = "SELECT * FROM produit WHERE code_ax = :code_ax";
    $params = array(
        ":code_ax" => $code_ax,
    );
    try {

        $sth = $dbh -> prepare($sql);
        $sth -> execute($params);
        $produits = $sth -> fetch(PDO::FETCH_ASSOC);
        $code_ax = $produits["code_ax"];
        $code_movex = $produits["code_movex"];
        $designation_produit = $produits["designation_produit"];
        $reference_commerciale = $produits["reference_commerciale"];


    } catch (PDOException $ex) {
        die("Erreur de la récupération des données :". $ex -> getMessage());
    }
    
}
//Si le formulaire n'est pas soumis alors on est redirigé vers la page "Gestions des produits"
if ($annuler) {
    header("Location: gestion_produit.php");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification d'un produit</title>
</head>
<body>
    <?php include "tete_page.php";?>
    
    <h1 style = "text-align: center; margin-top: 20px;">Modification d'un produit </h1>

    <hr style="border: 1px solid black; width: 100%;">

    <form action="" method="POST" class="container mt-5" style="max-width: 600px;">
    <div class="mb-3">
        <label class="form-label">Code AX :</label>
        <input type="text" name="code_ax" class="form-control" value="<?= htmlspecialchars($code_ax) ?>" maxlength="20" minlength="3" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Code Movex :</label>
        <input type="text" name="code_movex" class="form-control" value="<?= htmlspecialchars($code_movex) ?>" maxlength="20" minlength="3" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Désignation :</label>
        <input type="text" name="designation_produit" class="form-control" value="<?= htmlspecialchars($designation_produit) ?>" maxlength="20" minlength="3" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Référence commerciale :</label>
        <input type="text" name="reference_commerciale" class="form-control" value="<?= htmlspecialchars($reference_commerciale) ?>" maxlength="20" minlength="3" required>
    </div>

    <div class="d-flex justify-content-center gap-2">
        <button type="submit" name="submit" class="btn btn-success">Enregistrer</button>
        <button type="submit" name="annuler" class="btn btn-secondary">Annuler</button>
    </div>
</form>

</body>
</html>