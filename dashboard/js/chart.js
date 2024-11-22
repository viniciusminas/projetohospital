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
                    beginAtZero: true  // Garante que o gráfico comece em 0
                }
            }
        }
    });
}
