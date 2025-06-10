<?php

include "functions/db_functions.php";

$dbh = db_connect();

$code_ax = isset($_POST["code_ax"]) ? $_POST["code_ax"]:null;
$code_movex = isset($_POST["code_movex"]) ? $_POST["code_movex"]:null;
$designation_produit = isset($_POST["designation_produit"]) ? $_POST["designation_produit"]:null;
$reference_commerciale = isset($_POST["reference_commerciale"]) ? $_POST["reference_commerciale"]:null;
$submit = isset($_POST["submit"]);
$annuler = isset($_POST["annuler"]);

if ($submit) {
    $sql = "INSERT INTO produit (code_ax, code_movex, designation_produit, reference_commerciale) VALUES (:code_ax, :code_movex, :designation_produit, :reference_commerciale)";
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
}

if ($annuler) {
    header("Location: gestion_produit.php");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Ajout d'un produit</title>
</head>
<body>

    <h1> Ajouter un produit :</h1>
    <form action = "" method = "POST">

        <p>Code AX : <br><input type = "text" name = "code_ax"></p>

        <p>Code Movex : <br><input type = "text" name = "code_movex"></p>

        <p>Désignation : <br><input type = "text" name = "designation_produit"></p>

        <p>Référence commerciale : <br><input type = "text" name = "reference_commerciale"></p>

        <input type="submit" name = "submit" value = "Enregistrer">

        <input type="submit" name = annuler value = "annuler">

    </form>
</body>
</html>