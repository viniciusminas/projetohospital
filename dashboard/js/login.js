document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('loginForm');
    const loginMessage = document.getElementById('loginMessage');
    const loginButton = loginForm.querySelector('button[type="submit"]');

    // Evento de envio do formulário
    loginForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Evita recarregar a página

        const formData = new FormData(loginForm);
        console.log([...formData.entries()]); // Debug: veja os valores enviados

        // Exibe feedback visual de carregamento
        loginButton.disabled = true;
        loginMessage.textContent = 'Processando...';

        // Envia requisição para o servidor
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
                    alert(data.sucesso); // Exibe mensagem de sucesso
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
                loginButton.disabled = false; // Reabilita o botão após a requisição
            });
    });

    // Função para exibir mensagens de erro
    function mostrarMensagemErro(mensagem) {
        loginMessage.textContent = mensagem;
        loginMessage.style.color = 'red'; // Adiciona cor para destacar o erro
    }
});
