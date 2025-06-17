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

    $params = array(
        ":code_ax" => $code_ax,
        ":stock_secu_attendu" => $stock_secu_attendu,
        ":stock_secu_reel" => $stock_secu_reel,
        ":commentaire_stock" => $commentaire_stock,
    );
    try {
        
        $sth = $dbh -> prepare("CALL update_stock_produit(:code_ax, :stock_secu_attendu, :stock_secu_reel, :commentaire_stock)");
        $sth -> execute($params);

    } catch (PDOException $ex) {
        die("Erreur lors de la modification des données :". $ex -> getMessage());
    }
    header("Location: gestion_stock.php");
    exit;

    //Sinon affichage des données déjà connu dans les inputs
} else {
    
    $params = array(
        ":code_ax" => $code_ax,
    );
    try {

        $sth = $dbh -> prepare("CALL get_produit_by_code_ax(:code_ax)");
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

    <form action="" method = "POST" style = "margin-left: 20px;">

        <p>Code AX : <br><input type = "text" name = "code_ax" value ="<?php echo htmlspecialchars($code_ax) ?>" disabled maxlength="20" minlength="3"></p>

        <p>Code Movex : <br><input type = "text" name = "code_movex" value = "<?php echo htmlspecialchars($code_movex) ?>" disabled maxlength="20" minlength="3"></p>

        <p>Désignation : <br><input type = "text" name = "designation_produit" value = "<?php echo htmlspecialchars($designation_produit) ?>" disabled maxlength="20" minlength="3"></p>

        <p>Référence commerciale : <br><input type = "text" name = "reference_commerciale" value = "<?php echo htmlspecialchars($reference_commerciale) ?>" disabled maxlength="20" minlength="3"></p>

        <p>Stock sécurité attendu :<input type = "number" name = "stock_secu_attendu" value = "<?php echo htmlspecialchars($stock_secu_attendu) ?>" maxlength="20" minlength="3"></p>
        
        <p>Stock sécurité réel :<input type = "number" name = "stock_secu_reel" value = "<?php echo htmlspecialchars($stock_secu_reel) ?>" maxlength="20" minlength="3"></p>

        <p>Commentaires :<input type = "text" name = "commentaire_stock" value = "<?php echo htmlspecialchars($commentaire_stock) ?>" maxlength="30" minlength="3"></p>

        <input type = "submit" name = "submit" value = "Enregistrer">

        <input type="submit" name = annuler value = "annuler">

    </form>
</body>
</html>