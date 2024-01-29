<?php

session_start();
$_SESSION['authenticated'] = true;

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil Pixel War Rezoleo</title>
    <link rel="stylesheet" href="stylesheets/main.css">
</head>
<body>
    <header>
        <nav class="menu">
            <div class="menu-links">
                <a class="lien logo-link" id="case_logo">
                    <img src="images/rezoleo_logo.png" alt="logo" id="logo" style="width : 80px">
                </a>
                <a class="lien" href="index.html" id="case_1">
                    <p>Accueil</p>
                </a>
                <a class="lien" href="pixel_war.html" id="case_2">
                    <p>Pixel War</p>
                </a>
                <div class="text-container">
                    <p>Bienvenue dans la Pixel War Rezoleo</p>
                </div>
            </div>
        </nav>
    </header>
    <div class="main-body">
        <h1>Bienvenue dans la Pixel War Rezoleo</h1>
        <p>
            Faites place à votre imagination et montrez vos talents en terme de dessins et de créativité.
            N'hésitez pas à contacter d'autres personnes pour créer des alliances et des stratégies. <br>
            <b>Nous voulons voir vos plus belles créations !!!</b>
        </p>
        <p>
            Vous avez accés à tout un tas de couleurs sur une palette de pixel pouvant s'aggrandir.
            Avec dans le futur la possibilité de téléverser des images pour les transformer en calque.
        </p>
        <p>
            Toute personne participant à la Pixel War Rezoleo doit cependant respecter la règle suivante :<br>
            <b><U>Chaque joueur est responsable de ces dessins, si un dessin est jugé peu convenable l'administrateur
                se réserve le droit de le supprimer et en cas de récidive de bannir le joueur.
            </b></U>
        </p>
    </div>
</body>
</html>