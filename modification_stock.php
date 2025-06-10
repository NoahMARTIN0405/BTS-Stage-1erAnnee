<?php

include "functions/db_functions.php";

$dbh = db_connect();

$code_ax = isset($_GET["code_ax"]) ? $_GET["code_ax"]:null;
if ($code_ax == null) {
    die ("Erreur lors de la récupération de l'id dans l'url");
}

$stock_secu_attendu = isset($_POST["stock_secu_attendu"]) ? $_POST["stock_secu_attendu"]:null;
$stock_secu_reel = isset($_POST["stock_secu_reel"]) ? $_POST["stock_secu_reel"]:null;
$commentaire_stock = isset($_POST["commentaire_stock"]) ? $_POST["commentaire_stock"]:null;
$submit = isset($_POST["submit"]);
$annuler = isset($_POST["annuler"]);

if ($submit) {

    $sql = "UPDATE produit SET stock_secu_attendu = :stock_secu_attendu, stock_secu_reel = :stock_secu_reel, commentaire_stock = :commentaire_stock WHERE code_ax =:code_ax";
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
        $stock_secu_attendu = $produits["stock_secu_attendu"];
        $stock_secu_reel = $produits["stock_secu_reel"];
        $commentaire_stock = $produits["commentaire_stock"];
        
        


    } catch (PDOException $ex) {
        die("Erreur de la récupération des données :". $ex -> getMessage());
    }
}
if ($annuler) {
    header("Location: gestion_stock.php");
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Modification d'un produit</title>
</head>
<body>
    <h1>Modification d'un produit :</h1>

    <form action="" method = "POST">

        <p>Code AX : <br><input type = "text" name = "code_ax" value ="<?php echo htmlspecialchars($code_ax) ?>" disabled></p>

        <p>Code Movex : <br><input type = "text" name = "code_movex" value = "<?php echo htmlspecialchars($code_movex) ?>" disabled></p>

        <p>Désignation : <br><input type = "text" name = "designation_produit" value = "<?php echo htmlspecialchars($designation_produit) ?>" disabled></p>

        <p>Référence commerciale : <br><input type = "text" name = "reference_commerciale" value = "<?php echo htmlspecialchars($reference_commerciale) ?>" disabled></p>

        <p>Stock sécurité attendu :<input type = "number" name = "stock_secu_attendu" value = "<?php echo htmlspecialchars($stock_secu_attendu) ?>"></p>
        
        <p>Stock sécurité réel :<input type = "number" name = "stock_secu_reel" value = "<?php echo htmlspecialchars($stock_secu_reel) ?>"></p>

        <p>Commentaires :<input type = "text" name = "commentaire_stock" value = "<?php echo htmlspecialchars($commentaire_stock) ?>"></p>

        <input type = "submit" name = "submit" value = "Enregistrer">

        <input type="submit" name = annuler value = "annuler">

    </form>
</body>
</html>