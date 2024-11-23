document.addEventListener("DOMContentLoaded", () => {
    const botaoAtualizar = document.getElementById('atualizar');
    const selectSetor = document.getElementById('setor');
    const tabelaDashboard = document.getElementById('tabelaDashboard');
    const canvas = document.getElementById('graficoDashboard');
    const ctx = canvas.getContext('2d');
    let graficoAtual = null;

    botaoAtualizar.addEventListener('click', async () => {
        const setorSelecionado = selectSetor.value;

        if (!setorSelecionado) {
            alert('Por favor, selecione um setor.');
            return;
        }

        try {
            // Faz a requisição para o servidor
            const resposta = await fetch(`data.php?setor=${encodeURIComponent(setorSelecionado)}`);
            const dados = await resposta.json();

            // Verifica se há erro
            if (resposta.status !== 200 || dados.erro) {
                alert(dados.erro || 'Erro ao carregar os dados.');
                return;
            }

            console.log("Dados recebidos:", dados);

            // Atualiza o gráfico
            atualizarGrafico(dados);

            // Atualiza a tabela
            atualizarTabela(dados);
        } catch (erro) {
            console.error('Erro na requisição:', erro);
            alert('Erro ao carregar os dados do servidor.');
        }
    });

    // Função para atualizar o gráfico
    function atualizarGrafico(dados) {
        if (graficoAtual) {
            graficoAtual.destroy();
        }

        const perguntas = dados.map(d => d.pergunta);
        const medias = dados.map(d => parseFloat(d.media_resposta));

        graficoAtual = new Chart(ctx, {
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
                plugins: {
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        formatter: value => value.toFixed(2),
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                    }
                }
            }
        });
    }

    // Função para atualizar a tabela
    function atualizarTabela(dados) {
        tabelaDashboard.innerHTML = dados.map(d => `
            <tr>
                <td>${d.pergunta}</td>
                <td>${d.setor}</td>
                <td>-</td>
                <td>${parseFloat(d.media_resposta).toFixed(2)}</td>
                <td>${d.total_respostas}</td>
            </tr>
        `).join('');
    }
});
