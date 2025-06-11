<?php

//Récupération de ma fonction de connexion à ma BDD
include "functions/db_functions.php";
$dbh = db_connect();

// Récupération des produits
$sql_produit = "SELECT * FROM produit";
try {
    $sth = $dbh->prepare($sql_produit);
    $sth->execute();
    $produits = $sth->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    die("Erreur SQL produit : " . $ex->getMessage());
}

// Récupération des productions
$sql = "SELECT * FROM production";
try {
    $sth = $dbh->prepare($sql);
    $sth->execute();
    $productions = $sth->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    die("Erreur SQL production : " . $ex->getMessage());
}

// Récupération des engagements (livraisons prévues)
$sql_engagement = "SELECT * FROM engagement";
try {
    $sth = $dbh->prepare($sql_engagement);
    $sth->execute();
    $engagements = $sth->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    die("Erreur SQL engagement : " . $ex->getMessage());
}

// Organiser les productions par produit et date
$production_par_produit = [];
foreach ($productions as $prod) {
    $id = $prod["code_ax"];
    $date = date("d/m/Y", strtotime($prod["date_production"]));
    $production_par_produit[$id][$date] = ($production_par_produit[$id][$date] ?? 0) + $prod["qte_production"];
}

// Organiser les engagements par produit et date
$engagement_par_produit = [];
foreach ($engagements as $eng) {
    $id = $eng["code_ax"];
    $date = date("d/m/Y", strtotime($eng["date_engagement"]));
    $engagement_par_produit[$id][$date] = ($engagement_par_produit[$id][$date] ?? 0) + $eng["qte_engagement"];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Planning Production & Engagement</title>
    <style>
        table {
            width: 100%;
            border: white;
        }
        th {
            color: white;
            padding: 8px;
            text-align: center;
            background-color: #00ae4e;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:nth-child(odd) {
            background-color: #ffffff;
        }
        th, td {
            text-align: center;
            width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

    </style>
</head>
<body>
<?php include "tete_page.php"; ?>

<h1 style = "text-align: center; margin-top: 20px;">Plan de production </h1>

<hr style="text-align: center;border: 1px solid black; width: 100%;">

<div style="text-align: center; margin: 20px 0;">
    <button onclick="changerSemaine(-1)">⬅️ Semaine précédente</button>
    <button onclick="changerSemaine(1)">Semaine suivante ➡️</button>
    <div id="semaine-label" style="margin: 10px 0; font-weight: bold;"></div>
</div>

<table id="planning">
    <thead>
        <tr>
            <th>Code AX</th>
            <th>Code MOVEX</th>
            <th>Désignation</th>
            <th>Référence commerciale</th>
            <th>&nbsp</th>
            <?php
            $dates = [];
            $startDate = new DateTime();
            if ($startDate->format('N') != 1) {
                $startDate->modify('last monday');
            }

            for ($i = 0; $i < 365; $i++) {
                $date = clone $startDate;
                $date->modify("+$i days");
                $formatted = $date->format("d/m/Y");
                $dates[] = $formatted;
                echo "<th class='col-" . ($i + 1) . "'>$formatted</th>";
            }
            ?>
            <th>Tps prod</th>
            <th>Durée cycle</th>
            <th>Total semaine</th>
            <th>Réalisé</th>
            <th>Reste à fabriquer</th>
            <th>Commentaires</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produits as $produit): 
            $id_produit = $produit["code_ax"];
        ?>
            <tr>
                <td><?= htmlspecialchars($produit["code_ax"]) ?></td>
                <td><?= htmlspecialchars($produit["code_movex"]) ?></td>
                <td><?= htmlspecialchars($produit["designation_produit"]) ?></td>
                <td><?= htmlspecialchars($produit["reference_commerciale"]) ?></td>
                <td>Production</td>
                <?php foreach ($dates as $i => $date): 
                    $qte = $production_par_produit[$id_produit][$date] ?? '';
                    $class = $qte !== '' ? 'has-production' : '';
                    echo "<td class='col-" . ($i + 1) . " $class'>" . ($qte !== '' ? $qte : '') . "</td>";
                endforeach; ?>
            </tr>
            <tr>
                <td>&nbsp</td>
                <td>&nbsp</td>
                <td>&nbsp</td>
                <td>&nbsp</td>
                <td class="row-label">Livraison prévue</td>
                <?php foreach ($dates as $i => $date): 
                    $qte = $engagement_par_produit[$id_produit][$date] ?? '';
                    $class = $qte !== '' ? 'has-engagement' : '';
                    echo "<td class='col-" . ($i + 1) . " $class'>" . ($qte !== '' ? $qte : '') . "</td>";
                endforeach; ?>
                <td>&nbsp</td>
                <td>&nbsp</td>
                <td>&nbsp</td>
                <td>&nbsp</td>
                <td>&nbsp</td>
                <td>&nbsp</td>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
<script>
    const joursParSemaine = 7;
    const totalJours = 365;
    let semaineCourante = 0;

    function changerSemaine(direction) {
        const maxSemaine = Math.floor((totalJours - 1) / joursParSemaine);
        semaineCourante += direction;
        if (semaineCourante < 0) semaineCourante = 0;
        if (semaineCourante > maxSemaine) semaineCourante = maxSemaine;
        afficherSemaine(semaineCourante);
    }

    function afficherSemaine(semaine) {
        for (let i = 1; i <= totalJours; i++) {
            const debut = semaine * joursParSemaine + 1;
            const fin = debut + joursParSemaine - 1;
            const visible = (i >= debut && i <= fin);
            const cellules = document.querySelectorAll('.col-' + i);
            cellules.forEach(cell => {
                cell.style.display = visible ? '' : 'none';
            });
        }
        document.getElementById("semaine-label").textContent = "Semaine " + (semaine + 1);
    }

    // Afficher la première semaine
    afficherSemaine(semaineCourante);
</script>
</html>
