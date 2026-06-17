<?php
session_start();
require 'config.php';

$message = '';
$message_type = '';

// Si l'utilisateur est déjà connecté, redirection
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = trim($_POST['mail'] ?? '');
    $mdp = $_POST['mdp'] ?? '';

    // Validation des données
    if (empty($mail) || empty($mdp)) {
        $message = 'Veuillez remplir tous les champs.';
        $message_type = 'error';
    } else {
        try {
            // Chercher l'utilisateur par email
            $stmt = $bdd->prepare('SELECT id, nom, prenom, mail, mdp, role FROM user_g2b WHERE mail = ?');
            $stmt->execute([$mail]);
            $user = $stmt->fetch();

            if ($user && password_verify($mdp, $user['mdp'])) {
                // Mot de passe correct - créer la session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nom'] = $user['nom'];
                $_SESSION['prenom'] = $user['prenom'];
                $_SESSION['mail'] = $user['mail'];
                $_SESSION['role'] = $user['role'];

                $message = 'Connexion réussie ! Redirection en cours...';
                $message_type = 'success';
                header('Refresh: 2; URL=dashboard.php');
            } else {
                $message = 'E-mail ou mot de passe incorrect.';
                $message_type = 'error';
            }
        } catch (PDOException $e) {
            $message = 'Erreur lors de la connexion : ' . $e->getMessage();
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
    <title>Connexion — Pitwall Mercedes</title>
    <link rel="stylesheet" href="assets/styles.css">
    <style>
        .auth-card { max-width:420px; margin:60px auto; padding:28px; background:rgba(0,0,0,0.35); border-radius:12px; }
        .auth-card label{ display:block; margin-top:12px; color:var(--mercedes-silver); }
        .auth-card input { width:100%; padding:12px 14px; margin-top:8px; border-radius:8px; border:1px solid rgba(255,255,255,0.06); background:transparent; color:var(--accent-text); }
        .auth-actions{ margin-top:20px; display:flex; gap:12px; justify-content:center; }
    </style>
</head>
<body>
    <?php include 'navbar.html'; ?>

    <main class="ui-block">
        <br>
        <section class="auth-section">
            <div class="auth-card">
                <h2 style="text-align:center; color:var(--petronas-teal);">Connexion</h2>

                <?php if ($message): ?>
                    <div style="padding:12px; margin-bottom:16px; border-radius:8px; text-align:center; color:white; background:<?php echo $message_type === 'success' ? 'rgba(76,175,80,0.8)' : 'rgba(244,67,54,0.8)'; ?>;">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="login.php">
                    <label for="mail">Adresse e‑mail</label>
                    <input id="mail" name="mail" type="email" required value="<?php echo htmlspecialchars($_POST['mail'] ?? ''); ?>">

                    <label for="mdp">Mot de passe</label>
                    <input id="mdp" name="mdp" type="password" required>

                    <div class="auth-actions">
                        <button type="submit" class="connexion-form-button">Se connecter</button>
                        <a href="register.php" class="inscription-form-button">S'inscrire</a>
                    </div>
                </form>
            </div>
        </section>
        <footer>© <?php echo date('Y'); ?> Pitwall Mercedes</footer>
    </main>
</body>
</html>

