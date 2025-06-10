
<?php
session_start();

$deconnexion = isset($_POST["submit"]);

if ($deconnexion) {
    if (isset($_SESSION["username"])) {
        $username = $_SESSION["username"];
        session_unset();
        session_destroy();
        setcookie(session_name(),"",-1,"/");
    }
    header("Location: accueil.php");
    
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class = "tetePage">
        <div class = "logo">    
            <img src="" alt="logo.png">
        </div>
        <div class = "titre">
            <h1>ACTIA Aerospace</h1>
        </div>
        <div class="deconnexion">
            <p>Connecté en tant que : <?php echo $_SESSION["username"] ?></p>

            <form action="" method = "POST">
        
                <input type = "submit" name = "submit" value = "Se déconnecter" >
    
            </form>
        </div>
    </div>
    <nav>
        <ul>
            <li><a href="gestion_produit.php">Gestion des produits</a></li>
            <li><a href="gestion_stock.php">Gestion des stocks</a></li>
            <li><a href="gestion_effectif.php">Gestion des effectifs</a></li>
            <li><a href="saisie_engagement.php">Saisie engagement</a></li>
            <li><a href="saisie_production.php">Saisie production</a></li>
            <li><a href="plan_production.php">Plan de production</a></li>
        </ul>
    </nav>
</body>
</html>