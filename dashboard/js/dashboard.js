let graficoInstancia;

document.getElementById('atualizar').addEventListener('click', () => {
    const setor = document.getElementById('setor').value;

    if (!setor) {
        alert('Selecione um setor para atualizar.');
        return;
    }

    const botao = document.getElementById('atualizar');
    botao.innerHTML = 'Carregando...';
    botao.disabled = true;

    fetch('data.php', {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${seuToken}`
        },
        body: JSON.stringify({ setor }) // Envia o parâmetro "setor" no corpo da requisição
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro ao buscar dados do dashboard');
            }
            return response.json();
        })
        .then(dados => {
            atualizarTabela(dados);
            atualizarGrafico(dados);
        })
        .catch(error => {
            console.error('Erro:', error);
        })
        .finally(() => {
            botao.innerHTML = 'Atualizar';
            botao.disabled = false;
        });
    
});


function atualizarTabela(dados) {
    const tabela = document.getElementById('tabelaAvaliacoes');
    tabela.innerHTML = '';

    dados.forEach(dado => {
        const linha = `
            <tr>
                <td>${dado.pergunta}</td>
                <td>${dado.setor || 'Não informado'}</td> 
                <td>${dado.dispositivo || '1'}</td>
                <td>${dado.media_resposta ? parseFloat(dado.media_resposta).toFixed(2) : '0.00'}</td>
                <td>${dado.total_respostas || 0}</td>
            </tr>
        `;
        tabela.innerHTML += linha;
    });
}

// atualiza o gráfico com os dados recebidos
function atualizarGrafico(dados) {
    const ctx = document.getElementById('graficoAvaliacoes').getContext('2d');

    //destroi o gráfico existente, se houver
    if (graficoInstancia) {
        graficoInstancia.destroy();
    }

    const labels = dados.map(dado => dado.pergunta);
    const valores = dados.map(dado => parseFloat(dado.media_resposta));

    // Cria um novo gráfico
    graficoInstancia = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Média das Avaliações',
                data: valores,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    enabled: true
                },
                datalabels: {
                    color: '#000',
                    anchor: 'center',
                    align: 'end',
                    formatter: (value) => value.toFixed(2)
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMax: 12 //tamanho do gráfico, para nao ultrapassar as barras
                }
            }
        },
        plugins: [ChartDataLabels]
    });
    
}

