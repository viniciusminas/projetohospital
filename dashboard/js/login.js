document.addEventListener("DOMContentLoaded", () => {
    const jwt = localStorage.getItem('jwtToken');

    // Define o comportamento de exibição com base no token
    if (!jwt) {
        document.getElementById("loginContainer").style.display = "block";
        document.getElementById("dashboardContainer").style.display = "none";
    } else {
        document.getElementById("loginContainer").style.display = "none";
        document.getElementById("dashboardContainer").style.display = "block";

        // Exemplo: carregar dados do dashboard
        carregarDashboard(jwt);
    }

    document.getElementById('loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        try {
            const response = await fetch('create.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ username, password })
            });

            console.log('Status da resposta:', response.status);

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.erro || 'Erro ao autenticar');
            }

            const data = await response.json();
            if (data.token) {
                localStorage.setItem('jwtToken', data.token); // Salva o token
                document.getElementById("loginContainer").style.display = "none";
                document.getElementById("dashboardContainer").style.display = "block";

                carregarDashboard(data.token); // Atualiza o dashboard
            } else {
                throw new Error(data.erro || 'Erro desconhecido');
            }
        } catch (error) {
            console.error('Erro:', error);
            document.getElementById('loginMessage').textContent = error.message || 'Erro ao fazer login.';
        }
    });
});

async function carregarDashboard(jwt) {
    try {
        const response = await fetch('data.php', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${jwt}`,
                'Content-Type': 'application/json'
            }
        });

        console.log('Status da resposta:', response.status);

        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.erro || 'Erro ao carregar dashboard');
        }

        const data = await response.json();
        console.log('Dados do Dashboard:', data);
    } catch (error) {
        console.error('Erro ao carregar dashboard:', error);
    }
}
