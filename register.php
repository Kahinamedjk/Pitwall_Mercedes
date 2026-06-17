<?php
require 'config.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $mail = trim($_POST['mail'] ?? '');
    $mdp = $_POST['mdp'] ?? '';

    // Validation des données
    if (empty($nom) || empty($prenom) || empty($mail) || empty($mdp)) {
        $message = 'Tous les champs sont obligatoires.';
        $message_type = 'error';
    } elseif (strlen($mdp) < 6) {
        $message = 'Le mot de passe doit contenir au moins 6 caractères.';
        $message_type = 'error';
    } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $message = 'L\'adresse e-mail n\'est pas valide.';
        $message_type = 'error';
    } else {
        try {
            // Vérifier si l'email existe déjà
            $stmt_check = $bdd->prepare('SELECT id FROM user_g2b WHERE mail = ?');
            $stmt_check->execute([$mail]);

            if ($stmt_check->fetch()) {
                $message = 'Cet e-mail est déjà utilisé.';
                $message_type = 'error';
            } else {
                // Hasher le mot de passe
                $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);

                // Insérer l'utilisateur
                $stmt = $bdd->prepare('INSERT INTO user_g2b (nom, prenom, mail, mdp, role) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([$nom, $prenom, $mail, $mdp_hash, 'user']);

                $message = 'Inscription réussie ! Redirection en cours.';
                $message_type = 'success';

                // Réinitialiser le formulaire
                $nom = '';
                $prenom = '';
                $mail = '';
                header('Refresh: 2; URL=dashboard.php');
            }
        } catch (PDOException $e) {
            $message = 'Erreur lors de l\'inscription : ' . $e->getMessage();
            $message_type = 'error';
        }
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Inscription — Pitwall Mercedes</title>
    <link rel="stylesheet" href="assets/styles.css">
    <style>
        .auth-card { max-width:520px; margin:60px auto; padding:28px; background:rgba(0,0,0,0.35); border-radius:12px; }
        .auth-card label{ display:block; margin-top:12px; color:var(--mercedes-silver); }
        .auth-card input { width:100%; padding:12px 14px; margin-top:8px; border-radius:8px; border:1px solid rgba(255,255,255,0.06); background:transparent; color:var(--accent-text); }
        .auth-actions{ margin-top:20px; display:flex; gap:12px; justify-content:center; }
    </style>
</head>
<body>
    <?php include 'navbar.html'; ?>
    <br>

    <main class="ui-block">
        <section class="auth-section">
            <div class="auth-card">
                <h2 style="text-align:center; color:var(--petronas-teal);">Création de compte</h2>

                <?php if ($message): ?>
                    <div style="padding:12px; margin-bottom:16px; border-radius:8px; text-align:center; color:white; background:<?php echo $message_type === 'success' ? 'rgba(76,175,80,0.8)' : 'rgba(244,67,54,0.8)'; ?>;">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="register.php">
                    <label for="nom">Nom</label>
                    <input id="nom" name="nom" type="text" required value="<?php echo htmlspecialchars($nom ?? ''); ?>">

                    <label for="prenom">Prénom</label>
                    <input id="prenom" name="prenom" type="text" required value="<?php echo htmlspecialchars($prenom ?? ''); ?>">

                    <label for="mail">Adresse e‑mail</label>
                    <input id="mail" name="mail" type="email" required value="<?php echo htmlspecialchars($mail ?? ''); ?>">

                    <label for="mdp">Mot de passe</label>
                    <input id="mdp" name="mdp" type="password" required>

                    <div class="auth-actions">
                        <button type="submit" class="connexion-form-button">S'inscrire</button>
                        <a href="login.php" class="inscription-form-button">Connexion</a>
                    </div>
                </form>
            </div>
        </section>
        <footer>© <?php echo date('Y'); ?> Pitwall Mercedes</footer>
    </main>
</body>
</html>

