document.addEventListener('DOMContentLoaded', () => {
    const messagesContainer = document.getElementById('assistantMessages');
    const inputField = document.getElementById('assistantInput');
    const sendButton = document.getElementById('sendMessageBtn');
    let lastRequest = 0;

    function addMessage(content, isUser) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${isUser ? 'user-message' : 'bot-message'}`;
        messageDiv.textContent = content;
        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function sendMessage() {
        const message = inputField.value.trim();
        if (!message) return;

        // Client-side rate limiting
        const now = Date.now();
        if (now - lastRequest < 10000) { // 10s cooldown
            addMessage('Veuillez attendre 10 secondes avant d\'envoyer un autre message.', false);
            return;
        }
        lastRequest = now;

        addMessage(message, true);
        inputField.value = '';
        inputField.disabled = true;
        sendButton.disabled = true;

        fetch('api/assistant.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message })
        })
        .then(response => response.json())
        .then(data => {
            inputField.disabled = false;
            sendButton.disabled = false;

            if (data.success) {
                addMessage(data.response, false);
            } else {
                const errorMsg = data.error.includes('Limite de requêtes dépassée')
                    ? data.error // e.g., "Réessayez dans 10 secondes"
                    : 'Erreur: ' + (data.error || 'Échec de la réponse');
                addMessage(errorMsg, false);
                console.error('Chatbot error:', data.error);
            }
        })
        .catch(error => {
            inputField.disabled = false;
            sendButton.disabled = false;
            addMessage('Erreur réseau: ' + error.message, false);
            console.error('Network error:', error);
        });
    }

    sendButton.addEventListener('click', sendMessage);
    inputField.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendMessage();
    });
});
