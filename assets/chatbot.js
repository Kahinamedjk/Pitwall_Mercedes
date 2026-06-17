// ========== CHATBOT MISTRAL ==========
let chatHistory = [];

document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('chatbot-toggle');
    const chatbotWindow = document.getElementById('chatbot-window');
    const closeBtn = document.getElementById('chatbot-close');
    const sendBtn = document.getElementById('chatbot-send');
    const input = document.getElementById('chatbot-input');
    const messages = document.getElementById('chatbot-messages');

    // Vérifier que tous les éléments existent sur la page
    if (!toggleBtn || !chatbotWindow || !closeBtn || !sendBtn || !input || !messages) {
        return; // Sortir si le chatbot n'est pas présent sur cette page
    }

    // Ouvrir/Fermer chatbot
    toggleBtn.addEventListener('click', () => {
        chatbotWindow.classList.toggle('active');
        if (chatbotWindow.classList.contains('active') && messages.children.length === 0) {
            addMessage('bot', '👋 Bonjour ! Je suis l\'assistant Mercedes. Comment puis-je vous aider ?');
        }
    });

    closeBtn.addEventListener('click', () => {
        chatbotWindow.classList.remove('active');
    });

    // Envoyer message
    function sendMessage() {
        const text = input.value.trim();
        if (!text) return;

        addMessage('user', text);
        input.value = '';
        sendBtn.disabled = true;
        sendBtn.textContent = '⏳';

        fetch('chatbot.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                message: text,
                history: chatHistory
            })
        })
        .then(res => res.json())
        .then(data => {
            sendBtn.disabled = false;
            sendBtn.textContent = '➤';
            if (data.success) {
                addMessage('bot', data.reply);
                if (data.history) {
                    chatHistory.push({role: 'user', content: text});
                    chatHistory.push(data.history[0]);
                }
            } else {
                addMessage('bot', '❌ Désolé, une erreur est survenue. Réessayez plus tard.');
            }
        })
        .catch(() => {
            sendBtn.disabled = false;
            sendBtn.textContent = '➤';
            addMessage('bot', '❌ Erreur de connexion. Vérifiez votre serveur.');
        });
    }

    sendBtn.addEventListener('click', sendMessage);
    input.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendMessage();
    });

    function addMessage(role, content) {
        const div = document.createElement('div');
        div.className = 'message ' + role;
        div.textContent = content;
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    }
});

// ========== FAQ ACCORDION ==========
function toggleFAQ(element) {
    const item = element.parentElement;
    item.classList.toggle('active');
}