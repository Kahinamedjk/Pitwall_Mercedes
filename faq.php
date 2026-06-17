<?php
session_start();
require_once 'config.php';

// Récupérer les FAQ approuvées depuis la BDD
$faqs = [];
try {
    $stmt = $bdd->query('SELECT id, question, answer, created_at FROM faq_g2b WHERE status = "approved" ORDER BY id ASC');
    $faqs = $stmt->fetchAll();
} catch (PDOException $e) {
    // Fallback silencieux
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Pitwall Mercedes</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>

    <?php include 'navbar.html'; ?>

    <main class="ui-block">

        <header>
            <h1>❓ FAQ</h1>
            <p class="page-description">Trouvez des réponses à vos questions ou posez-en une nouvelle !</p>
        </header>

        <!-- Liste des FAQ -->
        <section class="auth-section">
            <h2 style="text-align:center; color:var(--petronas-teal); margin-bottom:30px;">Questions Fréquentes</h2>

            <div class="faq-container">

                <?php if (empty($faqs)): ?>
                    <p style="text-align:center; color:#888;">Aucune question pour le moment. Soyez le premier à en poser une !</p>
                <?php else: ?>
                    <?php foreach ($faqs as $faq): ?>
                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <?php echo htmlspecialchars($faq['question']); ?>
                                <span class="arrow">▼</span>
                            </div>
                            <div class="faq-answer">
                                <?php if (!empty($faq['answer'])): ?>
                                    <?php echo htmlspecialchars($faq['answer']); ?>
                                <?php else: ?>
                                    <em style="color:#888;">En attente d'une réponse.</em>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </section>

        <!-- Formulaire de soumission -->
        <section class="auth-section" style="margin-top:30px;">
            <h2 style="text-align:center; color:var(--petronas-teal); margin-bottom:20px;">💡 Poser une question</h2>
            <p style="text-align:center; color:#aaa; margin-bottom:20px;">
                Vous ne trouvez pas votre question ? Soumettez-la, nous y répondrons !
            </p>
            <div id="faq-submit-form" style="max-width:600px; margin:0 auto;">
                <div class="faq-form-group">
                    <label for="faq-question-input" style="display:block; color:var(--mercedes-silver); margin-bottom:8px;">Votre question</label>
                    <textarea id="faq-question-input" rows="3" placeholder="Ex: Comment puis-je acceder aux donnees en temps reel ?" maxlength="500" style="width:100%; padding:14px; border-radius:10px; border:1px solid rgba(255,255,255,0.1); background:rgba(0,0,0,0.3); color:var(--accent-text); font-size:1rem; resize:vertical;"></textarea>
                    <div style="display:flex; justify-content:flex-end; margin-top:5px;">
                        <span id="char-count" style="color:#666; font-size:0.85rem;">0 / 500</span>
                    </div>
                </div>
                <div class="faq-form-group" style="margin-top:15px;">
                    <label for="faq-email-input" style="display:block; color:var(--mercedes-silver); margin-bottom:8px;">Votre email (optionnel, pour etre notifie)</label>
                    <input type="email" id="faq-email-input" placeholder="vous@exemple.com" style="width:100%; padding:14px; border-radius:10px; border:1px solid rgba(255,255,255,0.1); background:rgba(0,0,0,0.3); color:var(--accent-text); font-size:1rem;">
                </div>
                <div style="text-align:center; margin-top:20px;">
                    <button id="faq-submit-btn" class="connexion-button" style="font-size:1rem; padding:15px 40px;">Envoyer ma question</button>
                </div>
                <div id="faq-submit-message" style="display:none; margin-top:15px; padding:12px; border-radius:8px; text-align:center;"></div>
            </div>
        </section>


        <footer>© <?php echo date('Y'); ?> Pitwall Mercedes — Propulsé par Mistral AI</footer>

    </main>

    <!-- Chatbot UI -->
    <?php include 'chatbot_ui.html'; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var input = document.getElementById('faq-question-input');
        var charCount = document.getElementById('char-count');
        var emailInput = document.getElementById('faq-email-input');
        var submitBtn = document.getElementById('faq-submit-btn');
        var messageDiv = document.getElementById('faq-submit-message');
        if (!input || !submitBtn) return;

        input.addEventListener('input', function() {
            var len = this.value.length;
            charCount.textContent = len + ' / 500';
            charCount.style.color = len > 450 ? '#ff6b6b' : '#666';
        });

        submitBtn.addEventListener('click', function() {
            var question = input.value.trim();
            if (!question) { showMsg('Veuillez entrer une question.', 'error'); return; }
            if (question.length < 10) { showMsg('Minimum 10 caracteres.', 'error'); return; }

            this.disabled = true;
            this.textContent = 'Envoi...';

            fetch('api/faq_submit.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ question: question, submitted_by: emailInput.value.trim() })
            })
            .then(function(r) { return r.json(); })
            .then(function(d) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Envoyer ma question';
                if (d.success) {
                    showMsg(d.message, 'success');
                    input.value = '';
                    charCount.textContent = '0 / 500';
                } else {
                    showMsg(d.error || 'Erreur.', 'error');
                }
            })
            .catch(function() {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Envoyer ma question';
                showMsg('Erreur de connexion.', 'error');
            });
        });

        function showMsg(text, type) {
            messageDiv.style.display = 'block';
            messageDiv.textContent = text;
            messageDiv.style.background = type === 'success' ? 'rgba(76,175,80,0.8)' : 'rgba(244,67,54,0.8)';
            messageDiv.style.color = 'white';
            setTimeout(function() { messageDiv.style.display = 'none'; }, 5000);
        }
    });
    </script>


</body>
</html>