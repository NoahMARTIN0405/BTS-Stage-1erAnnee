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

    $params = array(
        ":code_ax" => $code_ax,
        ":code_movex" => $code_movex,
        ":designation_produit" => $designation_produit,
        ":reference_commerciale" => $reference_commerciale,
    );
    try {
        
        $sth = $dbh -> prepare("CALL update_produit(:code_ax, :code_movex, :designation_produit, :reference_commerciale)");
        $sth -> execute($params);

    } catch (PDOException $ex) {
        die("Erreur lors de la modification des données :". $ex -> getMessage());
    }
    header("Location: gestion_produit.php");
    exit;
    
    //Sinon on affiche seulement les données déjà connu dans nos inputs
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

    <form action="" method = "POST" style = "margin-left: 20px;">

        <p>Code AX : <br><input type = "text" name = "code_ax" value ="<?php echo htmlspecialchars($code_ax) ?>" maxlength="20" minlength="3"></p>

        <p>Code Movex : <br><input type = "text" name = "code_movex" value = "<?php echo htmlspecialchars($code_movex) ?>" maxlength="20" minlength="3"></p>

        <p>Désignation : <br><input type = "text" name = "designation_produit" value = "<?php echo htmlspecialchars($designation_produit) ?>" maxlength="20" minlength="3"></p>

        <p>Référence commerciale : <br><input type = "text" name = "reference_commerciale" value = "<?php echo htmlspecialchars($reference_commerciale) ?>" maxlength="20" minlength="3"></p>

        <input type="submit" name = "submit" value = "Enregistrer">

        <input type="submit" name = annuler value = "annuler">

    </form>
</body>
</html>