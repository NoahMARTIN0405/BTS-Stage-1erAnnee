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
    
    <form action="" method ="POST" style = "margin-left: 20px;">

        <p>Code AX : <br><input type = "text" name = "code_ax" value = "<?php echo $produits["code_ax"] ?? ''?>"></p>

        <p>Code Movex : <br><input type = "text" name = "code_movex" value = "<?php echo $produits["code_movex"] ?? ''?>"></p>

        <p>Désignation : <br><input type = "text" name = "designation_produit" value = "<?php echo $produits["designation_produit"] ?? ''?>"></p>

        <p>Référence commerciale : <br><input type = "text" name = "reference_commerciale" value = "<?php echo $produits["reference_commerciale"] ?? ''?>"></p>

        <p>Date de livraison :<input type="date" name = "date_engagement"></p>

        <p>Quantité à livrer<input type="number" name = "qte_engagement"></p>

        <input type = "submit" name = "submit">

        <input type="submit" name = annuler value = "annuler">
    </form>
</body>
</html>