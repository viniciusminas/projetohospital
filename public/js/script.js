let perguntaAtual = 0;
let perguntas = [];
let respostaSelecionada = null;
let timerInterval;
let tempoRestante = 5;

function carregarPerguntasDoServidor() {
    document.getElementById('loading-spinner').style.display = 'block';

    fetch('../src/perguntas.php') //requisicao
        .then(response => {
            if (!response.ok) {
                throw new Error('Falha na requisição: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            console.log(data); 
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
    const setorElement = document.getElementById('setor');
    if (!setorElement) {
        console.error("Erro: elemento 'setor' não encontrado.");
        alert("Selecione o setor antes de enviar sua resposta.");
        return;
    }

    const setorValue = setorElement.value;
    if (!setorValue) {
        alert("Selecione um setor antes de continuar.");
        return;
    }

    if (respostaSelecionada === null) {
        alert("Por favor, selecione uma nota antes de continuar.");
        return;
    }

    const dadosEnvio = {
        pergunta_id: perguntas[perguntaAtual]?.id_pergunta,
        id_setor: setorValue,
        id_dispositivo: getDispositivoId(), // Pega o ID do dispositivo
        resposta: respostaSelecionada,
        feedback_textual: document.getElementById('feedback')?.value || null 
    };

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

    //só habilita o botão se o setor e pelo menos uma nota for selecionado
    const setorElement = document.getElementById('setor');
    const botaoProxima = document.getElementById("botao-proxima");

    if (setorElement && setorElement.value) {
        botaoProxima.disabled = false;
    } else {
        botaoProxima.disabled = true;
    }

    console.log("Nota selecionada:", respostaSelecionada);
}

// Função para limpar seleção visual
function limparSelecao() {
    document.querySelectorAll('.rating-scale div').forEach(el => el.classList.remove('selected'));
}

// feedback final (opcional)
function exibirFeedbackFinal() {
    const container = document.querySelector('.container');
    container.innerHTML = `
        <h1>Obrigado pela sua avaliação!</h1>
        <p>Sua avaliação é muito importante para nós. Por favor, deixe um feedback adicional, se desejar:</p>
        <div class="feedback-container">
            <textarea id="feedback" placeholder="Escreva seu feedback aqui..."></textarea>
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
    return document.getElementById('dispositivo').value;  // campo oculto com ID do dispositivo
}

// Inicializa o carregamento das perguntas ao carregar a página
carregarPerguntasDoServidor();

// Adiciona evento ao botão "Próxima"
document.getElementById("botao-proxima").addEventListener("click", proximaPergunta);