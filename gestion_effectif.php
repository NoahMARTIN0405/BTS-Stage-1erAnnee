<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des effectifs</title>
</head>
<body>
    <!--Permet d'afficher la tête de page sur toutes les pages -->
    <?php include "tete_page.php"; ?>
    <h1 style = "text-align: center; margin-top: 20px;">Gestion des effectifs</h1>
    
    <hr style="border: 1px solid black; width: 100%;">
    
    <div style = "display: flex; justify-content: center; gap: 20px; margin: 20px auto;">
    <a href = "tableau_effectif.php"><button style = "padding: 20px 40px; font-size: 20px; cursor: pointer; background-color: #00ae4e; color: white;">Tableau des effectifs</button></a>
    <a href="saisie_absence.php"><button style = "padding: 20px 40px; font-size: 20px; cursor: pointer; background-color: #00ae4e; color: white;">En cours (absence)</button></a>
    </div>
</body>
</html>