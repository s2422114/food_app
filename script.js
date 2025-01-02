function sendType(type) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'chat.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            const chatOutput = document.getElementById('chat-output');
            chatOutput.innerHTML += `<p>ユーザ：${type}</p>`;
            setTimeout(() => {
                chatOutput.innerHTML += `<p>チャットボット：${response.response}</p>`;
                chatOutput.scrollTop = chatOutput.scrollHeight;
            }, 1000); // 1 second delay
        }
    };
    xhr.send(`type=${type}`);
}
