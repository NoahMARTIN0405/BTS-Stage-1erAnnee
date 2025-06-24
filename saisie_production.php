<?php

//Récupération de ma fonction de connexion à ma BDD
include "functions/db_functions.php";

$dbh = db_connect();

//Récupération des inputs du formulaire de recherche 
$search_code_ax = isset($_POST["search_code_ax"]) ? $_POST["search_code_ax"]:null;
$search = isset($_POST["search"]);

//Récupération des inputs du formualire d'insertion 
$code_ax = isset($_POST["code_ax"]) ? $_POST["code_ax"]:null;
$code_movex = isset($_POST["code_movex"]) ? $_POST["code_movex"]:null;
$designation_produit = isset($_POST["designation_produit"]) ? $_POST["designation_produit"]:null;
$reference_commerciale = isset($_POST["reference_commerciale"]) ? $_POST["reference_commerciale"]:null;
$date_production = isset($_POST["date_production"]) ? $_POST["date_production"]:null;
$qte_production = isset($_POST["qte_production"]) ? $_POST["qte_production"]:null;
$submit = isset($_POST["submit"]);
$annuler = isset($_POST["annuler"]);

$sql = "CALL GetAllProduits()";
try { 
    $sth = $dbh -> prepare($sql);
    $sth -> execute();
    $rows = $sth -> fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex){
    die (" Erreur lors de la récupération de la liste". $ex -> getMessage());
}
//Si le formulaire est soumis alors on "INSERT" les données présentes dans les inputs dans notre table "production" 
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
    <!--Permet d'afficher la tête de page sur toutes les pages -->
    <?php include "tete_page.php"?>

    <h1 style = "text-align: center; margin-top: 20px;">Saisie production</h1>

    <hr style="border: 1px solid black; width: 100%;">

    <h2 style = "margin-left: 20px">Entrez votre code AX :</h2>

    <form action="" method = "POST">
        

    <input list="code_ax" name = "search_code_ax" placeholder = "Rechercher votre code AX" style = "margin: 20px;" >
        <datalist id = "code_ax">
             <?php
                    foreach ($rows as $row) {
                        echo "<option value='".$row["code_ax"]."'>";
                    }
                ?>
        </datalist>

        <input type ="submit" name = "search" value = "Rechercher" id = "search_input"></p>
    
    </form> 

<?php
    //Si mon formulaire de recherche est soumis alors on récupère les infos du produit dont le code AX correspond au code AX tapé dans la barre de recherche
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
?>
    <form action="" method="post" class="container mt-5" style="max-width: 600px;">
    <div class="mb-3">
        <label class="form-label">Code AX :</label>
        <input type="text" name="code_ax" class="form-control" value="<?= htmlspecialchars($produits["code_ax"] ?? '') ?>" maxlength="20" minlength="3">
    </div>

    <div class="mb-3">
        <label class="form-label">Code Movex :</label>
        <input type="text" name="code_movex" class="form-control" value="<?= htmlspecialchars($produits["code_movex"] ?? '') ?>" maxlength="20" minlength="3">
    </div>

    <div class="mb-3">
        <label class="form-label">Désignation :</label>
        <input type="text" name="designation_produit" class="form-control" value="<?= htmlspecialchars($produits["designation_produit"] ?? '') ?>" maxlength="20" minlength="3">
    </div>

    <div class="mb-3">
        <label class="form-label">Référence commerciale :</label>
        <input type="text" name="reference_commerciale" class="form-control" value="<?= htmlspecialchars($produits["reference_commerciale"] ?? '') ?>" maxlength="20" minlength="3">
    </div>

    <div class="mb-3">
        <label class="form-label">Date de production :</label>
        <input type="date" name="date_production" class="form-control" maxlength="20" minlength="3">
    </div>

    <div class="mb-3">
        <label class="form-label">Quantité production :</label>
        <input type="number" name="qte_production" class="form-control" maxlength="20" minlength="3">
    </div>

    <div class="d-flex justify-content-center gap-2">
        <button type="submit" name="submit" class="btn btn-success">Enregistrer</button>
        <button type="submit" name="annuler" class="btn btn-secondary">Annuler</button>
    </div>
</form>

<?php
}
?>
<script>
    const searchInput = document.getElementById("search_input");
    const listItems = document.querySelectorAll("#search_code_ax option");

    searchInput.addEventListener("input", function () {
    const filter = this.value.toLowerCase();

    listItems.forEach(item => {
    const text = item.textContent.toLowerCase();
    item.style.display = text.includes(filter) ? "block" : "none";
  });
});
</script>
</body>
</html>