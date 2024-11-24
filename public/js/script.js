let perguntaAtual = 0;
let perguntas = [];
let respostaSelecionada = null;
let timerInterval;
let tempoRestante = 5;


function obterParametroDaURL(nome) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(nome);
}

// Capturando o parâmetro necessário (disp)
const dispositivo = obterParametroDaURL('disp');

// Verificando se o parâmetro 'disp' existe
if (!dispositivo) {
    console.error('Parâmetro "disp" (dispositivo) não encontrado na URL.');
    alert('Erro: Dispositivo não especificado. Certifique-se de acessar a URL correta.');
} else {
    console.log(`ID do dispositivo: ${dispositivo}`);
}


function carregarPerguntasDoServidor() {
    document.getElementById('loading-spinner').style.display = 'block';

    const dispositivoId = obterParametroDaURL('disp'); // Obter o ID do dispositivo da URL
    if (!dispositivoId) {
        console.error('ID do dispositivo não encontrado na URL');
        document.getElementById('loading-spinner').style.display = 'none';
        document.getElementById('pergunta-texto').innerText = 'ID do dispositivo ausente';
        return;
    }

    fetch(`../src/perguntas.php?disp=${dispositivoId}`) // Requisição para pegar perguntas do dispositivo
        .then(response => {
            if (!response.ok) {
                throw new Error('Falha na requisição: ' + response.statusText);
            }
            return response.json(); // Converte a resposta para JSON
        })
        .then(data => {
            console.log("Resposta do servidor:", data);
            document.getElementById('loading-spinner').style.display = 'none';

            if (!data || !Array.isArray(data) || data.length === 0) {
                document.getElementById('pergunta-texto').innerText = 'Nenhuma pergunta disponível';
                return;
            }

            perguntas = data;
            perguntaAtual = 0;
            carregarPergunta();
        })
        .catch(error => {
            console.error('Erro na requisição AJAX:', error);
            document.getElementById('loading-spinner').style.display = 'none';
            document.getElementById('pergunta-texto').innerText = 'Nenhuma pergunta disponível';
        });
}



function carregarPergunta() {
    if (perguntas.length > 0 && perguntaAtual < perguntas.length) {
        const pergunta = perguntas[perguntaAtual];
        document.getElementById('pergunta-texto').innerText = pergunta.texto_pergunta; 
        document.getElementById('numero-pergunta').innerText = perguntaAtual + 1; 
        respostaSelecionada = null;
        limparSelecao();
        iniciarTimer();
    } else {
        document.getElementById('pergunta-texto').innerText = 'Nenhuma pergunta disponível';
    }
}

function enviarResposta() {
    if (respostaSelecionada === null || respostaSelecionada === undefined) {
        alert("Por favor, selecione uma nota antes de continuar.");
        return;
    }

    const perguntaId = perguntas[perguntaAtual]?.id_pergunta;

    if (!perguntaId) {
        console.error('Erro: ID da pergunta não encontrado.');
        alert('Erro: ID da pergunta não disponível.');
        return;
    }

    const feedbackTextual = document.getElementById('feedback_textual')?.value || null;

    const dadosEnvio = {
        pergunta_id: perguntaId,
        id_dispositivo: dispositivo,
        resposta: respostaSelecionada,
        feedback_textual: feedbackTextual
    };

    //depuração
    console.log('Dados enviados:', dadosEnvio);

    fetch('../src/respostas.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(dadosEnvio)
    })
        .then(response => response.json())
        .then(data => {
            if (data.sucesso) {
                console.log("Resposta salva com sucesso:", data);
            } else {
                console.error("Erro ao salvar resposta:", data.erro);
                alert("Erro ao salvar sua resposta. Tente novamente.");
            }
        })
        .catch(error => {
            console.error("Erro na requisição:", error);
            alert("Erro ao comunicar com o servidor.");
        });
}


function proximaPergunta() {
    if (respostaSelecionada === null) {
        alert("Por favor, selecione uma nota de 0 a 10.");
        return;
    }

    enviarResposta(); //só avanca se enviar a resposta primeiro.

    if (perguntaAtual < perguntas.length - 1) {
        perguntaAtual++;
        carregarPergunta();
    } else {
        exibirFeedbackFinal();
    }
}

// timer (em decisão se deixarei esse timer)
function iniciarTimer() {
    tempoRestante = 5; //reiniciar o time
    document.getElementById("timer").innerText = `Tempo restante: ${tempoRestante}s`;

    timerInterval = setInterval(() => {
        tempoRestante--;
        document.getElementById("timer").innerText = `Tempo restante: ${tempoRestante}s`;

        if (tempoRestante <= 0) {
            clearInterval(timerInterval);
            document.getElementById("botao-proxima").disabled = false;
        }
    }, 1000);
}

function setResposta(valor) {
    respostaSelecionada = valor;

    document.querySelectorAll('.rating-scale div').forEach(div => div.classList.remove('selected'));
    document.querySelectorAll('.rating-scale div')[valor - 1].classList.add('selected');

    // Habilita o botão apenas se uma nota for selecionada
    const botaoProxima = document.getElementById("botao-proxima");
    botaoProxima.disabled = false;

    console.log("Nota selecionada:", respostaSelecionada);
}

function limparSelecao() {
    document.querySelectorAll('.rating-scale div').forEach(el => el.classList.remove('selected'));
}

function exibirFeedbackFinal() {
    const container = document.querySelector('.container');
    container.innerHTML = `
        <h1>Obrigado pela sua avaliação!</h1>
        <p>Sua avaliação é muito importante para nós. Por favor, deixe um feedback adicional, se desejar:</p>
        <div class="feedback-container">
            <textarea id="feedback_textual" placeholder="Escreva seu feedback aqui..."></textarea>
            <button onclick="exibirMensagemAgradecimento()">Enviar Feedback</button>
        </div>
    `;
}

function exibirMensagemAgradecimento() {
    const container = document.querySelector('.container');
    container.innerHTML = `
        <h1>O Hospital Regional Alto Vale (HRAV) agradece sua resposta!</h1>
        <p>Sua avaliação é muito importante para nós, pois nos ajuda a melhorar continuamente nossos serviços.</p>
    `;

    

}

function getDispositivoId() {
    return document.getElementById('dispositivo').value; 
}

carregarPerguntasDoServidor();

document.getElementById("botao-proxima").addEventListener("click", proximaPergunta);