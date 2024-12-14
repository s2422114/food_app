document.querySelectorAll('.option').forEach(button => {
    button.addEventListener('click', () => {
        sendMessage(button.dataset.value);
    });
});

function sendMessage(userInput) {
    if (userInput.trim() === '') return;

    appendMessage('ユーザー', userInput);

    fetch('index.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `input=${encodeURIComponent(userInput)}`
    })
    .then(response => response.json())
    .then(data => {
        appendMessage('チャットボット', data.message);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function appendMessage(sender, message) {
    const chatBox = document.getElementById('chat-box');
    const messageElement = document.createElement('div');
    messageElement.className = 'message';
    messageElement.innerHTML = `<strong>${sender}:</strong> ${message}`;
    chatBox.appendChild(messageElement);
    chatBox.scrollTop = chatBox.scrollHeight;
}