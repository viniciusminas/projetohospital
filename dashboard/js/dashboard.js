// Arquivo: dashboard.js

// Adiciona evento para o botão de atualização
document.getElementById('atualizar').addEventListener('click', () => {
    const setor = document.getElementById('setor').value;

    // Verifica se um setor foi selecionado
    if (!setor) {
        alert('Selecione um setor para atualizar.');
        return;
    }

    const botao = document.getElementById('atualizar');
    botao.innerHTML = 'Carregando...';
    botao.disabled = true;

    // Faz a requisição para buscar os dados do backend
    fetch(`data.php?setor=${encodeURIComponent(setor)}`, {
        method: 'GET',
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro ao buscar dados do dashboard.');
            }
            return response.json();
        })
        .then(dados => {
            // Atualiza a tabela e o gráfico com os dados recebidos
            atualizarTabela(dados);
            atualizarGrafico(dados);
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao carregar os dados. Tente novamente mais tarde.');
        })
        .finally(() => {
            // Restaura o botão após a requisição
            botao.innerHTML = 'Atualizar';
            botao.disabled = false;
        });
});

// Atualiza a tabela com os dados recebidos
function atualizarTabela(dados) {
    const tabela = document.getElementById('tabelaDashboard');
    tabela.innerHTML = ''; // Limpa a tabela

    dados.forEach(dado => {
        const linha = `
            <tr>
                <td>${dado.pergunta}</td>
                <td>${dado.setor}</td>
                <td>Desktop</td> <!-- Campo fixo como "Desktop" -->
                <td>${parseFloat(dado.media_resposta).toFixed(2)}</td>
                <td>${dado.total_respostas}</td>
            </tr>
        `;
        tabela.innerHTML += linha;
    });
}

// Atualiza o gráfico com os dados recebidos
function atualizarGrafico(dados) {
    const ctx = document.getElementById('graficoDashboard').getContext('2d');

    // Extrai as perguntas e médias das respostas
    const perguntas = dados.map(d => d.pergunta);
    const medias = dados.map(d => parseFloat(d.media_resposta));

    // Cria o gráfico de barras
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: perguntas,
            datasets: [{
                label: 'Média das Respostas',
                data: medias,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true, // Garante que o eixo Y comece em zero
                }
            }
        }
    });
}
