<?php

include "functions/db_functions.php";

$dbh = db_connect();

$search_username = isset($_POST["search_username"]) ? $_POST["search_username"]:null;
$search = isset($_POST["search"]);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saisie absence</title>
</head>
<body>
    <?php include "tete_page.php"; ?>

    <h1 style = "text-align: center; margin-top: 20px;">Saisir une absence :</h1>

    <hr style="border: 1px solid black; width: 100%;">

    <h2> Entrez le nom d'utilisateur :</h2>

    <form action="" method="post">
    
        <input type="text" name = "search_username" placeholder = "Rechercher un utilisateur">

        <input type="submit" name = "search" value="rechercher">

    </form>
    <?php
if ($search) {
    $sql = "SELECT * FROM utilisateur WHERE username = :username";
    $params = array(
        ":username" => $search_username,
    );
    try {
        $sth = $dbh -> prepare($sql);
        $sth -> execute($params);
        $utilisateurs = $sth -> fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
        die("Erreur lros de la récupération des données utilisateur :" .$ex -> getMessage());
    }
    foreach ($utilisateurs as $utilisateur) {
        $username = $utilisateur["username"];
        $nom = $utilisateur["nom"];
        $prenom = $utilisateur["prenom"];
        $id_utilisateur = $utilisateur["id_utilisateur"];
    }
?>
    <form action="" method="post" style = "text-align: center;">
        <p>Nom d'utilisateur : <br><input type="text" name = "username" value = "<?php echo htmlspecialchars($username)?>" disabled></p>

        <p>Nom : <br><input type="text" name = "nom" value = "<?php echo htmlspecialchars($nom)?>" disabled></p>

        <p>Prénom : <br><input type="text" name = "prenom" value = "<?php echo htmlspecialchars($prenom)?>" disabled></p>

        <p>Absent le : <br><input type="date" name="date_absence"></p>

        <input type="hidden" name = "id_utilisateur" value = "<?php echo htmlspecialchars($id_utilisateur)?>">
        
        <p>Motif : <br>
        <select name="type_absence">
            <option value="AJ">Absences justifiée</option>
            <option value="AJ 1/2">Absence demi-journée justifiée</option>
            <option value="ALD">Absence longue durée</option>
            <option value="ANJ">Absence non justifiée</option>
            <option value="DEL">Déléguation</option>
            <option value="E">Ecole</option>
            <option value="FL">Flexi-Travail</option>
            <option value="FO">Formation</option>
            <option value="M">ARRET Maladie</option>
        </select>
        </p>
        <input type="submit" name = "submit" value = "Enregistrer">
    </form>
<?php

}
$id_utilisateur = isset($_POST["id_utilisateur"]) ? $_POST["id_utilisateur"]: null;
$type_absence = isset($_POST["type_absence"]) ? $_POST["type_absence"]: null;
$date_absence = isset($_POST["date_absence"]) ? $_POST["date_absence"]: null;
$submit = isset($_POST["submit"]);

if ($submit) {
    $sql = "INSERT INTO absence (`type_absence`, `date_absence`,`id_utilisateur`) VALUES (:type_absence,:date_absence,:id_utilisateur)";
    $params = array(
        ":type_absence" => $type_absence,
        ":date_absence" => $date_absence,
        ":id_utilisateur" => $id_utilisateur,
    );
    try {
        $sth = $dbh -> prepare($sql); 
        $sth -> execute($params);

    } catch (PDOException $ex) {
        die("erreur lors de l'insertion des données dans la table absence :".$ex -> getMessage());
    }
    header("Location: tableau_effectif.php")
}
?>
</body>
</html>