<?php

session_start();

include "functions/db_functions.php";

$dbh = db_connect();

$username = isset($_POST["username"]) ? $_POST["username"]:null;
$password = isset($_POST["password"]) ? $_POST["password"]:null;
$submit = isset($_POST["submit"]);

if ($submit) {
    $sql = "SELECT * FROM utilisateur";

    try {

        $sth = $dbh -> prepare($sql);
        $sth -> execute();
        $rows = $sth -> fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $ex) {
        die ("Erreur lors de la récupération des identifiants :". $ex -> getMessage());
    }

    if ($username == $rows["username"] && $password == $rows["mdp"]) {
        
        $_SESSION["username"] = $username ;
        header("Location: dashboard.php");
    
    } else {
        
        echo "Identifiants incorrects";
    
    }
    
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <title>Page de connexion </title>
</head>
<body>
    <h1>Connexion : </h1>

    <form action="" method = "POST">

        <input type = "text" name = "username">

        <input type = "password" name = "password">

        <input type = "submit" name = "submit" value = "Connexion">
    </form>
    <a href="dashboard.php"></a>
</body>
</html>