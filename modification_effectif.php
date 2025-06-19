<?php

//Récupération de ma fonction de connexion à ma BDD
include "functions/db_functions.php";

$dbh = db_connect();

//Récupération de l'id utilisateur dans l'URL 
$id_utilisateur= isset($_GET["id_utilisateur"]) ? $_GET["id_utilisateur"]: null;
if ($id_utilisateur == null ){
    die ("Erreur le paramètre n'est pas passé dans l'url");
}

//Récupération des "input" de mon formulaire
$unite_production = isset($_POST["unite_production"]) ? $_POST["unite_production"]: null;
$secteur = isset($_POST["secteur"]) ? $_POST["secteur"]: null;
$nom_prenom_manager = isset($_POST["nom_prenom_manager"]) ? $_POST["nom_prenom_manager"] : null;
$nom = isset($_POST["nom"]) ? $_POST["nom"]: null;
$prenom = isset($_POST["prenom"]) ? $_POST["prenom"]: null; 
$type_emploi = isset($_POST["type_emploi"]) ? $_POST["type_emploi"]: null;
$type_contrat = isset($_POST["type_contrat"]) ? $_POST["type_contrat"]: null;
$type_equipe = isset($_POST["type_equipe"]) ? $_POST["type_equipe"]: null;
$statut = isset($_POST["statut"]) ? $_POST["statut"]: null;
$submit = isset($_POST["submit"]);

//Si mon formulaire est soumis alors on "UPDATE" les données de notre table "utilisateur" par les données de nos input
if ($submit) {
    $sql = "UPDATE utilisateur SET nom =:nom, prenom =:prenom, unite_production =:unite_production, secteur =:secteur, nom_prenom_manager =:nom_prenom_manager, type_emploi =:type_emploi, type_contrat =:type_contrat, type_equipe =:type_equipe, statut =:statut";
    $params = array(
        ':nom' => $nom,
        ':prenom'=> $prenom,
        ':unite_production' => $unite_production,
        ':secteur' => $secteur,
        ':nom_prenom_manager' => $nom_prenom_manager,
        ':type_emploi' => $type_emploi,
        ':type_contrat' => $type_contrat,
        ':type_equipe' => $type_equipe,
        ':statut' => $statut,
        ':id_utilisateur' => $id_utilisateur,

    );
    try {
        $sth = $dbh -> prepare($sql);
        $sth -> execute($params);
    } catch (PDOException $ex) {
        die ("Erreur lors de la modification des données utilisateur : ". $ex -> getMessage());
    }
    header("Location: tableau_effectif.php");
    exit;

    //Sinon on sélectionne les données "utilisateur" déjà connu et on les affiche dans nos input
} else {
    $sql = "SELECT * FROM utilisateur WHERE id_utilisateur=:id_utilisateur";
    $params = array(
        'id_utilisateur' => $id_utilisateur,
    );
    try {
        $sth = $dbh -> prepare($sql);
        $sth -> execute($params);
        $user = $sth -> fetch(PDO::FETCH_ASSOC);
        $unite_production = $user["unite_production"];
        $secteur = $user["secteur"];
        $nom_prenom_manager = $user["nom_prenom_manager"];
        $nom = $user["nom"];
        $prenom = $user["prenom"];
        $type_emploi = $user["type_emploi"];
        $type_contrat = $user["type_contrat"];
        $type_equipe = $user["type_equipe"]; 
        $statut = $user["statut"];

    } catch (PDOException $ex ) {
        die ("Erreur lors de la récupération des données :". $ex -> getMessage());
    }
}
?> 
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification effectif </title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <?php include "tete_page.php"?>
    
    <h1 style = "text-align: center; margin-top: 20px;">Modification des effectifs</h1>

    <hr style="border: 1px solid black; width: 100%;">

    <form action="" method = "post" style = "text-align: center;">
        <p>Unité Production : <br><input type="text" name = "unite_production" value = "<?php echo $unite_production ?>" maxlength="20" minlength="3"></p>
        <p>Secteur : <br><input type="text" name = "secteur" value = "<?php echo $secteur ?>" maxlength="20" minlength="3"></p>
        <p>Nom-Prénom Manager : <br><input type="text" name = "nom_prenom_manager" value = "<?php echo $nom_prenom_manager ?>" maxlength="30" minlength="3"></p>
        <p>Nom : <br><input type="text" name = "nom" value = "<?php echo $nom ?>" maxlength="20" minlength="3"></p>
        <p>Prénom : <br><input type="text" name = "prenom" value = "<?php echo $prenom ?>" maxlength="20" minlength="3"></p>
        <p>Fiche Emploi : <br><input type="text" name = "type_emploi" value = "<?php echo $type_emploi ?>" maxlength="50" minlength="3"></p>
        <p>Type Contrat : <br><input type="text" name = "type_contrat" value = "<?php echo $type_contrat ?>" maxlength="20" minlength="3"></p>
        <p>Type Equipe : <br><input type="text" name = "type_equipe" value = "<?php echo $type_equipe ?>" maxlength="20" minlength="3"></p>
        <p>Statut : <br><input type="text" name = "statut" value = "<?php echo $statut ?>" maxlength="20" minlength="3"></p>
        <p><input type="submit" name = "submit"  value = "Enregistrer"></p>
    </form>
</body>
</html>