document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('loginForm');
    const loginMessage = document.getElementById('loginMessage');
    const loginButton = loginForm.querySelector('button[type="submit"]');

    loginForm.addEventListener('submit', function (event) {
        event.preventDefault(); 

        const formData = new FormData(loginForm);
        console.log([...formData.entries()]);

        loginButton.disabled = true;
        loginMessage.textContent = 'Processando...';

        fetch('login.php', {
            method: 'POST',
            body: formData,
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na resposta do servidor');
                }
                return response.json();
            })
            .then(data => {
                if (data.sucesso) {
                    alert(data.sucesso); 
                    window.location.href = 'dashboard.php';
                } else {
                    mostrarMensagemErro(data.erro || 'Erro ao autenticar.');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                mostrarMensagemErro('Erro ao processar sua solicitação. Tente novamente mais tarde.');
            })
            .finally(() => {
                loginButton.disabled = false;
            });
    });

    function mostrarMensagemErro(mensagem) {
        loginMessage.textContent = mensagem;
        loginMessage.style.color = 'red'; 
    }
});
