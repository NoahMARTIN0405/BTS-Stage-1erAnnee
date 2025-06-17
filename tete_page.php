
<?php
session_start();

$deconnexion = isset($_POST["submit"]);

//Si le bouton de deconnexion est cliqué alors on "détruit" proprement la session
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
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php"><img src="img/logo/ACTIA_QUADRI_RVB.png" width = 200px  alt=""></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarColor02">
      <ul class="navbar-nav me-auto">
        </li>
        <?php
        if($_SESSION["username"]){
          if ($_SESSION['id_usertype'] == 1){

            }else {
          ?> <li class="nav-item">
              <a class="nav-link" href="gestion_produit.php">Gestion des produits</a>
            </li><?php
          }
        }
        ?>
        <?php
        if($_SESSION["username"]){
          if ($_SESSION['id_usertype'] == 1){

            }else {
          ?> <li class="nav-item">
              <a class="nav-link" href="gestion_stock.php">Gestion des stocks</a>
            </li><?php
          }
        }
        ?>
        <?php
        if($_SESSION["username"]){
          if ($_SESSION['id_usertype'] == 3){

          ?> <li class="nav-item">
              <a class="nav-link" href="gestion_effectif.php">Gestion des effectifs</a>
            </li><?php
          }
        }
        ?>
        <?php
        if($_SESSION["username"]){
          if ($_SESSION['id_usertype'] == 1){

            }else {
          ?> <li class="nav-item">
              <a class="nav-link" href="gestion_engagement.php">Gestion des engagements</a>
            </li><?php
          }
        }
        ?>
        <?php
        if($_SESSION["username"]){
          if ($_SESSION['id_usertype'] == 1){

            }else {
          ?> <li class="nav-item">
              <a class="nav-link" href="saisie_production.php">Saisie des productions</a>
            </li><?php
          }
        }
        ?>
        <li class="nav-item">
          <a class="nav-link" href="plan_production.php">Plan de production</a>
        </li>
      </ul>
        <div style="display: grid; place-items: center">
            <p>Connecté en tant que : <span style ="color: #00ae4e; font-weight: bold;" ><?php echo $_SESSION["username"] ?></span></p>
            <form class="d-flex" method = "POST">
                <input type = "submit" name = "submit" class = "btn btn-success" value = "Se déconnecter" >
            </form>
        </div>
    </div>
  </div>
</nav>
</body>
</html>