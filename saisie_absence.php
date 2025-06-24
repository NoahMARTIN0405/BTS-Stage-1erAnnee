<?php
include "functions/db_functions.php";
$dbh = db_connect();

$search_username = isset($_POST["search_username"]) ? $_POST["search_username"] : null;
$search = isset($_POST["search"]);
$submit = isset($_POST["submit"]);

// Liste tous les utilisateurs pour le datalist
try {
    $sql_all = "CALL get_all_usernames()";
    $sth = $dbh->prepare($sql_all);
    $sth->execute();
    $utilisateurs = $sth->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    die("Erreur lors de la récupération des utilisateurs : " . $ex->getMessage());
}
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

    <h1 style="text-align: center; margin-top: 20px;">Saisir une absence :</h1>
    <hr style="border: 1px solid black; width: 100%;">

    <h2 style="margin-left: 20px;">Entrez le nom d'utilisateur :</h2>

    <form action="" method="post" style="margin-left: 20px;">
        <input list="username" name="search_username" placeholder="Rechercher un utilisateur" required>
        <datalist id="username">
            <?php foreach ($utilisateurs as $utilisateur): ?>
                <option value="<?= htmlspecialchars($utilisateur["username"]) ?>">
            <?php endforeach; ?>
        </datalist>
        <input type="submit" name="search" value="Rechercher">
    </form>

<?php
if ($search && $search_username) {
    $sql = "CALL get_utilisateur_by_username(:username)";
    $params = [":username" => $search_username];

    try {
        $sth = $dbh->prepare($sql);
        $sth->execute($params);
        $user_data = $sth->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
        die("Erreur lors de la récupération des données utilisateur : " . $ex->getMessage());
    }

    if ($user_data) {
        $nom_utilisateur = $user_data["username"];
        $nom = $user_data["nom"];
        $prenom = $user_data["prenom"];
        $id_utilisateur = $user_data["id_utilisateur"];
?>

    <form action="" method="post" class="container mt-5" style="max-width: 500px;">
    <div class="mb-3">
        <label class="form-label">Nom d'utilisateur :</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($nom_utilisateur) ?>" disabled>
    </div>

    <div class="mb-3">
        <label class="form-label">Nom :</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($nom) ?>" disabled>
    </div>

    <div class="mb-3">
        <label class="form-label">Prénom :</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($prenom) ?>" disabled>
    </div>

    <div class="mb-3">
        <label class="form-label">Absent le :</label>
        <input type="date" name="date_absence" class="form-control" required>
    </div>

    <input type="hidden" name="id_utilisateur" value="<?= htmlspecialchars($id_utilisateur) ?>">

    <div class="mb-4">
        <label class="form-label">Motif :</label>
        <select name="type_absence" class="form-select" required>
            <option value="AJ">Absence justifiée</option>
            <option value="AJ 1/2">Absence demi-journée justifiée</option>
            <option value="ALD">Absence longue durée</option>
            <option value="ANJ">Absence non justifiée</option>
            <option value="DEL">Délégation</option>
            <option value="E">École</option>
            <option value="FL">Flexi-Travail</option>
            <option value="FO">Formation</option>
            <option value="M">Arrêt Maladie</option>
        </select>
    </div>

    <div class="text-center">
        <button type="submit" name="submit" class="btn btn-success">Enregistrer</button>
    </div>
</form>


<?php
    } else {
        echo "<p style='color: red; text-align: center;'>Utilisateur non trouvé.</p>";
    }
}
?>

<?php
if ($submit) {
    $id_utilisateur = $_POST["id_utilisateur"] ?? null;
    $type_absence = $_POST["type_absence"] ?? null;
    $date_absence = $_POST["date_absence"] ?? null;

    if ($id_utilisateur && $type_absence && $date_absence) {
        $sql = "CALL insert_absence(:type_absence, :date_absence, :id_utilisateur)";
        $params = [
            ":type_absence" => $type_absence,
            ":date_absence" => $date_absence,
            ":id_utilisateur" => $id_utilisateur
        ];
        try {
            $sth = $dbh->prepare($sql);
            $sth->execute($params);
            header("Location: tableau_effectif.php");
            exit;
        } catch (PDOException $ex) {
            die("Erreur lors de l'insertion : " . $ex->getMessage());
        }
    } else {
        echo "<p style='color: red; text-align: center;'>Veuillez remplir tous les champs requis.</p>";
    }
}
?>
</body>
</html>
