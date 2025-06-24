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

<form action="" method="POST" class="container mt-5" style="max-width: 600px;">
    <div class="mb-3">
        <label class="form-label">Nom d'utilisateur :</label>
        <input type="text" name="username" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Mot de passe :</label>
        <input type="password" name="mdp" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Nom :</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Prénom :</label>
        <input type="text" name="prenom" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Unité Production :</label>
        <input type="text" name="unite_production" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Secteur :</label>
        <input type="text" name="secteur" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Nom-Prénom Manager :</label>
        <input type="text" name="nom_prenom_manager" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Type Emploi :</label>
        <input type="text" name="type_emploi" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Type Contrat :</label>
        <input type="text" name="type_contrat" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Type Équipe :</label>
        <input type="text" name="type_equipe" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Statut :</label>
        <input type="text" name="statut" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Type Utilisateur :</label>
        <select name="usertype" class="form-select" required>
            <option value="1">Utilisateur</option>
            <option value="2">Administrateur</option>
            <option value="3">Super-Administrateur</option>
        </select>
    </div>

    <div class="d-flex justify-content-center">
        <button type="submit" name="submit" class="btn btn-success">Enregistrer</button>
    </div>
</form>

</body>
</html>