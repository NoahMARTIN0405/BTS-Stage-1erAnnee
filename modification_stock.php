<?php

//Récupération de ma fonction de connexion à ma BDD
include "functions/db_functions.php";

$dbh = db_connect();

//Récupération du code AX dans l'URL
$code_ax = isset($_GET["code_ax"]) ? $_GET["code_ax"]:null;
if ($code_ax == null) {
    die ("Erreur lors de la récupération de l'id dans l'url");
}

// Récupération des inputs de mon formulaire
$stock_secu_attendu = isset($_POST["stock_secu_attendu"]) ? $_POST["stock_secu_attendu"]:null;
$stock_secu_reel = isset($_POST["stock_secu_reel"]) ? $_POST["stock_secu_reel"]:null;
$commentaire_stock = isset($_POST["commentaire_stock"]) ? $_POST["commentaire_stock"]:null;
$submit = isset($_POST["submit"]);
$annuler = isset($_POST["annuler"]);

//Si mon formulaire est soumis alors on "UPDATE" les données déjà connu dans notre table "produit" par les données présentes dans nos inputs
if ($submit) {
    $sql = "CALL update_stock_produit_by_code_ax(
        :code_ax, :stock_secu_attendu, :stock_secu_reel, :commentaire_stock
    )";
    $params = array(
        ":code_ax" => $code_ax,
        ":stock_secu_attendu" => $stock_secu_attendu,
        ":stock_secu_reel" => $stock_secu_reel,
        ":commentaire_stock" => $commentaire_stock,
    );
    try {
        
        $sth = $dbh -> prepare($sql);
        $sth -> execute($params);

    } catch (PDOException $ex) {
        die("Erreur lors de la modification des données :". $ex -> getMessage());
    }
    header("Location: gestion_stock.php");
    exit;

    //Sinon affichage des données déjà connu dans les inputs
} else {
    $sql = "CALL get_produit_by_code_ax(:code_ax)";
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
        $stock_secu_attendu = $produits["stock_secu_attendu"];
        $stock_secu_reel = $produits["stock_secu_reel"];  


    } catch (PDOException $ex) {
        die("Erreur de la récupération des données :". $ex -> getMessage());
    }
}
//Si le formulaire n'est pas soumis alors on est redirigé vers la page "Gestions des stocks"
if ($annuler) {
    header("Location: gestion_stock.php");
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification d'un produit</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>

    <?php include "tete_page.php";?>
    
    <h1 style = "text-align: center; margin-top: 20px;">Modification d'un stock</h1>

    <hr style="border: 1px solid black; width: 100%;">

<form action="" method="POST" class="container mt-5" style="max-width: 600px;">
    <div class="mb-3">
        <label class="form-label">Code AX :</label>
        <input type="text" name="code_ax" class="form-control" value="<?= htmlspecialchars($code_ax) ?>" maxlength="20" minlength="3" disabled>
    </div>

    <div class="mb-3">
        <label class="form-label">Code Movex :</label>
        <input type="text" name="code_movex" class="form-control" value="<?= htmlspecialchars($code_movex) ?>" maxlength="20" minlength="3" disabled>
    </div>

    <div class="mb-3">
        <label class="form-label">Désignation :</label>
        <input type="text" name="designation_produit" class="form-control" value="<?= htmlspecialchars($designation_produit) ?>" maxlength="20" minlength="3" disabled>
    </div>

    <div class="mb-3">
        <label class="form-label">Référence commerciale :</label>
        <input type="text" name="reference_commerciale" class="form-control" value="<?= htmlspecialchars($reference_commerciale) ?>" maxlength="20" minlength="3" disabled>
    </div>

    <div class="mb-3">
        <label class="form-label">Stock sécurité attendu :</label>
        <input type="number" name="stock_secu_attendu" class="form-control" value="<?= htmlspecialchars($stock_secu_attendu) ?>" maxlength="20" minlength="3" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Stock sécurité réel :</label>
        <input type="number" name="stock_secu_reel" class="form-control" value="<?= htmlspecialchars($stock_secu_reel) ?>" maxlength="20" minlength="3" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Commentaires :</label>
        <input type="text" name="commentaire_stock" class="form-control" value="<?= htmlspecialchars($commentaire_stock) ?>" maxlength="30" minlength="3">
    </div>

    <div class="d-flex justify-content-center gap-2">
        <button type="submit" name="submit" class="btn btn-success">Enregistrer</button>
        <button type="submit" name="annuler" class="btn btn-secondary">Annuler</button>
    </div>
</form>

</body>
</html>