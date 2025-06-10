<?php


include "functions/db_functions.php";

$dbh = db_connect();



$search_code_ax = isset($_POST["search_code_ax"]) ? $_POST["search_code_ax"]:null;
$search = isset($_POST["search"]);


if ($search) {
    $sql = "SELECT * FROM produit WHERE code_ax = :code_ax";
    $params = array(
        ":code_ax" => $search_code_ax,
    );
    try {

        $sth = $dbh -> prepare($sql);
        $sth -> execute($params);
        $produits = $sth -> fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $ex) {
        die("Erreur de la récupération des données :". $ex -> getMessage());
    }
}

$code_ax = isset($_POST["code_ax"]) ? $_POST["code_ax"]:null;
$code_movex = isset($_POST["code_movex"]) ? $_POST["code_movex"]:null;
$designation_produit = isset($_POST["designation_produit"]) ? $_POST["designation_produit"]:null;
$reference_commerciale = isset($_POST["reference_commerciale"]) ? $_POST["reference_commerciale"]:null;
$date_production = isset($_POST["date_production"]) ? $_POST["date_production"]:null;
$qte_production = isset($_POST["qte_production"]) ? $_POST["qte_production"]:null;
$submit = isset($_POST["submit"]);
$annuler = isset($_POST["annuler"]);

if ($submit) {
    $sql = "INSERT INTO production (date_production, qte_production, code_ax) VALUES (:date_production, :qte_production, :code_ax)";
    $params = array(
        ":date_production" => $date_production,
        ":qte_production" => $qte_production,
        ":code_ax" => $code_ax,
    );
    try {
        
        $sth = $dbh -> prepare($sql);
        $sth -> execute($params);
    } catch (PDOException $ex) {
        die("Erreur lors de l'insertion des données dans la table 'production' :" . $ex -> getMessage());
    }
     header("Location: saisie_production.php");
     exit;
}
if ($annuler) {
    header("Location: saisie_production.php");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saisie production :</title>
</head>
<body>

    <?php include "tete_page.php"?>

    <h1>Saisie production :</h1>

    <form action="" method = "POST">
        
        <p><input type = "text" name = "search_code_ax" placeholder = "Rechercher votre code AX">

        <input type ="submit" name = "search" value = "Rechercher"></p>

        <p>Code AX : <br><input type = "text" name = "code_ax" value ="<?php echo $produits["code_ax"] ?? ''?>"></p>

        <p>Code Movex : <br><input type = "text" name = "code_movex" value = "<?php echo $produits["code_movex"] ?? ''?>"></p>

        <p>Désignation : <br><input type = "text" name = "designation_produit" value = "<?php echo $produits["designation_produit"] ?? ''?>"></p>

        <p>Référence commerciale : <br><input type = "text" name = "reference_commerciale" value = "<?php echo $produits["reference_commerciale"] ?? ''?>"></p>

        <p>Date de production :<input type="date" name = "date_production"></p>

        <p>Quantité production :<input type="number" name = "qte_production"></p>

        <input type = "submit" name = "submit" value ='Enregistrer'>

        <input type="submit" name = annuler value = "annuler">

    
    </form>
</body>
</html>