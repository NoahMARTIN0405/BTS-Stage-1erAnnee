<?php

//Récupération de ma fonction de connexion à ma BDD
include "functions/db_functions.php";
$dbh = db_connect();

// Récupération des produits
$sql_produit = "CALL GetAllProduits()";
try {
    $sth = $dbh->prepare($sql_produit);
    $sth->execute();
    $produits = $sth->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    die("Erreur SQL produit : " . $ex->getMessage());
}

// Récupération des productions
$sql = "CALL GetAllProductions()";
try {
    $sth = $dbh->prepare($sql);
    $sth->execute();
    $productions = $sth->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    die("Erreur SQL production : " . $ex->getMessage());
}

// Récupération des engagements (livraisons prévues)
$sql_engagement = "CALL GetAllEngagements";
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
</head>
<body>
<style>
        table {
        width: 80%;
        margin: auto;
        border-collapse: collapse;
    }

    th, td {
        border: 1px solid black !important;
        padding: 8px;
        text-align: center;
        width: 100px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    th {
        color: white;
        background-color: #00ae4e;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:nth-child(odd) {
        background-color: #ffffff;
    }
</style>

<?php include "tete_page.php"; ?>

<h1 style = "text-align: center; margin-top: 20px;">Plan de production </h1>

<hr style="text-align: center;border: 1px solid black; width: 100%;">

<div style="text-align: center; margin: 20px 0;">
    <button onclick="changerSemaine(-1)">⬅️ Semaine précédente</button>
    <button onclick="changerSemaine(1)">Semaine suivante ➡️</button>

    <label for="select-semaine">Aller à la semaine :</label>
    <select id="select-semaine" onchange="selectionnerSemaine(this.value)">
        <!-- Options ajoutées dynamiquement -->
    </select>

    <div id="semaine-label" style="margin: 10px 0; font-weight: bold;"></div>
</div>
        <?php
        if(isset($_SESSION["username"])){
          if ($_SESSION['id_usertype'] == 1){

            }else {
          ?> <p style = "text-align: center;">
              <a href="gestion_engagement.php">Gestion des engagements</a>
              <a href="saisie_production.php">Saisie des productions</a>
            </p><?php
          }
        }
        ?>
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

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</body>
<script>
    const joursParSemaine = 7;
    const totalJours = 365;
    let semaineCourante = 0;
    const startDate = new Date(); // Date actuelle

    // Corriger le startDate pour tomber sur le lundi de la semaine en cours
    if (startDate.getDay() !== 1) {
        const jour = startDate.getDay() === 0 ? 7 : startDate.getDay();
        startDate.setDate(startDate.getDate() - (jour - 1));
    }

    function changerSemaine(direction) {
        const maxSemaine = Math.floor((totalJours - 1) / joursParSemaine);
        semaineCourante += direction;
        if (semaineCourante < 0) semaineCourante = 0;
        if (semaineCourante > maxSemaine) semaineCourante = maxSemaine;
        afficherSemaine(semaineCourante);
        document.getElementById("select-semaine").value = semaineCourante;
    }

    function selectionnerSemaine(semaine) {
        semaineCourante = parseInt(semaine);
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

        // Mise à jour du label
        const dateDebut = new Date(startDate);
        dateDebut.setDate(dateDebut.getDate() + semaine * joursParSemaine);
        const dateFin = new Date(dateDebut);
        dateFin.setDate(dateFin.getDate() + joursParSemaine - 1);
        const formatDate = d => d.toLocaleDateString('fr-FR');
        document.getElementById("semaine-label").textContent = 
            "Semaine " + (semaine + 1) + " (" + formatDate(dateDebut) + " - " + formatDate(dateFin) + ")";
    }

    function remplirSelect() {
        const select = document.getElementById('select-semaine');
        const maxSemaine = Math.floor((totalJours - 1) / joursParSemaine);

        for (let i = 0; i <= maxSemaine; i++) {
            const dateDebut = new Date(startDate);
            dateDebut.setDate(dateDebut.getDate() + i * joursParSemaine);
            const dateFin = new Date(dateDebut);
            dateFin.setDate(dateFin.getDate() + joursParSemaine - 1);

            const option = document.createElement('option');
            option.value = i;
            option.textContent = `Semaine ${i + 1} (${dateDebut.toLocaleDateString('fr-FR')} - ${dateFin.toLocaleDateString('fr-FR')})`;
            select.appendChild(option);
        }

        select.value = semaineCourante;
    }

    // Initialisation
    remplirSelect();
    afficherSemaine(semaineCourante);
</script>

</html>
