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
    $params = array(
        ":code_ax" => $code_ax,
        ":code_movex" => $code_movex,
        ":designation_produit" => $designation_produit,
        ":reference_commerciale" => $reference_commerciale,
    );
    try {

        $sth = $dbh -> prepare("CALL ajouter_produit(:code_ax, :code_movex, :designation_produit, :reference_commerciale)");
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
    
    <form action = "" method = "POST" style = "text-align: center;">

        <p>Code AX : <br><input type = "text" name = "code_ax" maxlength="20" minlength="3"></p>

        <p>Code Movex : <br><input type = "text" name = "code_movex" maxlength="20" minlength="3"></p>

        <p>Désignation : <br><input type = "text" name = "designation_produit" maxlength="20" minlength="3"></p>

        <p>Référence commerciale : <br><input type = "text" name = "reference_commerciale" maxlength="20" minlength="3"></p>

        <input type="submit" name = "submit" value = "Enregistrer">

        <input type="submit" name = annuler value = "annuler">

    </form>
</body>
</html>