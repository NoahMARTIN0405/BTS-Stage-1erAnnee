<?php

session_start();

//Récupération de ma fonction de connexion à ma BDD
include "functions/db_functions.php";

$dbh = db_connect();

//Récupération des inputs de mon formulaire 
$username = isset($_POST["username"]) ? $_POST["username"]:null;
$password = isset($_POST["password"]) ? $_POST["password"]:null;
$id_utilisateur = isset($_GET["id_utilisateur"]) ? $_GET["id_utilisateur"]: null;
$submit = isset($_POST["submit"]);

//Si mon formulaire est soumis, je récupère toute les valeurs de la table "utilisateur"
if ($submit) {
  $sql = "SELECT * FROM utilisateur WHERE username = :username and mdp = :mdp";
    $params = array(
        ":username" => $username,
        ":mdp" => $password,
    );
    try {

        $sth = $dbh -> prepare($sql);
        $sth -> execute($params);
        $rows = $sth -> fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $ex) {
        die ("Erreur lors de la récupération des identifiants :". $ex -> getMessage());
    }
    //Si mes saisies "username" ET "password" existent et sont liées, alors j'enregistre une nouvelle session
    if ($username == $rows["username"] && $password == $rows["mdp"]) {
        
        $_SESSION["username"] = $username ;
        $_SESSION["id_usertype"] = $rows["id_usertype"];
        $_SESSION["id_utilisateur"] = $rows["id_utilisateur"];
        header("Location: plan_production.php");
    
    //Sinon Affichage de "Identifiants incorrects"
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
    <link rel="stylesheet" href="css/main.css">
    <title>Page de connexion </title>
</head>
<body>
    <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f2f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .login-container {
      background-color: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
    }

    .login-container h2 {
      text-align: center;
      margin-bottom: 24px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
    }

    .form-group input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 16px;
    }

    .login-button {
      width: 100%;
      padding: 12px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .login-button:hover {
      background-color: #45a049;
    }

    .signup-link {
      text-align: center;
      margin-top: 16px;
    }

    .signup-link a {
      color: #007BFF;
      text-decoration: none;
    }

    .signup-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="login-container">
    <h2>Connexion</h2>
    <form action="" method="POST">
      <div class="form-group">
        <label for="username">Pseudo / Nom d'utilisateur</label>
        <input type="text" id="username" name="username" required maxlength="20" minlength="3">
      </div>

      <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required maxlength="20" minlength="3">
      </div>

      <button type="submit" name = "submit" class="login-button">Se connecter</button>

    </form>
  </div>
</body>
</html>