<?php
include "functions/db_functions.php";

$dbh = db_connect();

$username = isset($_POST["username"]) ? $_POST["username"]:null;
$password = isset($_POST["mdp"]) ? password_hash($_POST["mdp"], PASSWORD_DEFAULT):null;
$nom = isset($_POST["name"]) ? $_POST["name"]:null;
$prenom = isset($_POST["prenom"]) ? $_POST["prenom"]:null;
$unite_production = isset($_POST["unite_production"]) ? $_POST["unite_production"]:null;
$secteur = isset($_POST["secteur"]) ? $_POST["secteur"]:null;
$nom_prenom_manager = isset($_POST["nom_prenom_manager"]) ? $_POST["nom_prenom_manager"]:null;
$type_emploi = isset($_POST["type_emploi"]) ? $_POST["type_emploi"]:null;
$type_contrat = isset($_POST["type_contrat"]) ? $_POST["type_contrat"]:null;
$type_equipe = isset($_POST["type_equipe"]) ? $_POST["type_equipe"]:null;
$statut = isset($_POST["statut"]) ? $_POST["statut"]:null;
$usertype = isset($_POST["usertype"]) ? $_POST["usertype"]:null;
$submit = isset($_POST["submit"]);

// Si le formulaire est envoyé alors on créer un nouvel utilisateur
if ($submit) {
    $sql = "INSERT INTO utilisateur (`username`, `mdp`, `nom`, `prenom`, `unite_production`, `secteur`, `nom_prenom_manager`, `type_emploi`, `type_contrat`, `type_equipe`, `statut`, `id_usertype`) 
    VALUES (:username, :mdp ,:nom,:prenom,:unite_production,:secteur,:nom_prenom_manager,:type_emploi,:type_contrat,:type_equipe,:statut,:usertype)";
    $params = array(
        ':username' => $username,
        ':mdp' => $password,
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':unite_production' => $unite_production,
        ':secteur' => $secteur,
        ':nom_prenom_manager' => $nom_prenom_manager,
        ':type_emploi' => $type_emploi,
        ':type_contrat' => $type_contrat,
        ':type_equipe' => $type_equipe,
        ':statut' => $statut, 
        ':usertype' => $usertype,
    );
    try {
        $sth = $dbh -> prepare($sql); 
        $sth -> execute($params);
    } catch (PDOException $ex) {
        die("Erreur lors de l'insertion des données :". $ex -> getMessage());
    }
    header("Location: tableau_effectif.php");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php include "tete_page.php"; ?>
    
    <h1 style = "text-align: center; margin-top: 20px;">Création d'un effectif</h1>

    <hr style="border: 1px solid black; width: 100%;">

    <form action="" method="post" style = "text-align: center;">
        
    <p>Nom d'utilisateur : <br><input type="text" name = "username" required></p>

    <p>Mot de passe : <br><input type="text" name = "mdp" required></p>

    <p>Nom : <br><input type="text" name = "name" required></p>

    <p>Prénom : <br><input type="text" name = "prenom" required></p>
    
    <p>Unité Production : <br><input type="text" name = "unite_production" required></p>
    
    <p>Secteur : <br><input type="text" name = "secteur" required></p>
    
    <p>Nom-Prénom Manager : <br><input type="text" name = "nom_prenom_manager" required></p>
    
    <p>Type Emploi : <br><input type="text" name = "type_emploi" required></p>
    
    <p>Type Contrat : <br><input type="text" name = "type_contrat" required></p>
    
    <p>Type Equipe : <br><input type="text" name = "type_equipe" required></p>
    
    <p>Statut : <br><input type="text" name = "statut" required></p>

    <p>Type Utilisateur : <br>
    <select name = "usertype" >
        <option value="1">Utilisateur</option>
        <option value="2">Administrateur</option>
        <option value="3">Super-Administrateur</option>
    </select>
    </p>
    <input type="submit" name = "submit" value = "Enregistrer">
    </form>
</body>
</html>