function atualizarGrafico(dados) {
    const ctx = document.getElementById('graficoDashboard').getContext('2d');

    const perguntas = dados.map(d => d.pergunta);
    const medias = dados.map(d => parseFloat(d.media_resposta));

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
            scales: {
                y: {
                    beginAtZero: true,
                }
            }
        }
    });
}


function criarGraficoRespostas(respostas) {
    const perguntas = [];
    const contagemRespostas = [];

    respostas.forEach(resposta => {
        if (!perguntas.includes(resposta.texto_pergunta)) {
            perguntas.push(resposta.texto_pergunta);
            contagemRespostas.push(1);
        } else {
            const index = perguntas.indexOf(resposta.texto_pergunta);
            contagemRespostas[index]++;
        }
    });

    const ctx = document.getElementById('respostas-chart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: perguntas,
            datasets: [{
                label: 'Número de Respostas', 
                data: contagemRespostas, 
                backgroundColor: 'rgba(75, 192, 192, 0.2)',  
                borderColor: 'rgba(75, 192, 192, 1)',  
                borderWidth: 1 
            }]
        },
        options: {
            responsive: true,  // Torna o gráfico responsivo
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}
