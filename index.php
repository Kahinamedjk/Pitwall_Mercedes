<?php
// Page d'accueil — Pitwall Mercedes
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Pitwall Mercedes — Accueil</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>

    <?php include 'navbar.html'; ?>

    <main class="ui-block">

        <header>
            <h1>Pitwall Mercedes</h1>
            <p class="sub">Plateforme d'exploration du modèle F1 et des données de performance de l'équipe Mercedes‑AMG Petronas.</p>
        </header>

        <section class="pitwall-board">
            <div class="welcome" style="text-align:center;">
                <h2 style="color:var(--petronas-teal); margin-bottom:12px;">Bienvenue</h2>
                <p style="color:var(--mercedes-silver); line-height:1.6; max-width:900px; margin:0 auto;">
                    Ce site a pour objectif de présenter la monoplace Mercedes en 3D, fournir des informations sur les circuits,
                    et montrer des données de performance issues d'un pitwall pédagogique. Vous trouverez ici un modèle 3D, des
                    pages dédiées aux circuits et aux performances, ainsi que des outils de visualisation.
                </p>

                <div style="margin-top:30px; display:flex; gap:20px; justify-content:center; flex-wrap:wrap;">
                    <a href="login.php" class="connexion-button">Connexion</a>
                    <a href="register.php" class="inscription-button">Inscription</a>
                </div>
            </div>
        </section>

        <footer>© <?php echo date('Y'); ?> Pitwall Mercedes — Projet pédagogique</footer>

    </main>

    <?php include 'chatbot_ui.html'; ?>

</body>
</html>