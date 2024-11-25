<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliação de Serviços</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/script.js" defer></script>
    <link rel="icon" href="../img/logo.png" type="image/png">
    <img src="../img/logo.png" alt="Logotipo Hospital Regional" class="logo">
    </head>
<body onload="carregarPergunta()">
    
<button id="fullscreen-btn" onclick="alternarTelaCheia()">Tela Cheia</button>

    <div class="container">
        <p id="contador">Pergunta <span id="numero-pergunta">1</span></p>
        <p id="pergunta-texto">Carregando pergunta...</p>
        <p id="timer"></p>

        <!-- Spinner de carregamento -->
        <div id="loading-spinner" style="display: none;" class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Carregando...</span>
            </div>
        </div>

        <div class="rating-scale d-flex justify-content-center gap-2 mb-4">
            <div class="low" onclick="setResposta(1)">1</div>
            <div class="low" onclick="setResposta(2)">2</div>
            <div class="mid-low" onclick="setResposta(3)">3</div>
            <div class="mid-low" onclick="setResposta(4)">4</div>
            <div class="mid" onclick="setResposta(5)">5</div>
            <div class="mid" onclick="setResposta(6)">6</div>
            <div class="mid-high" onclick="setResposta(7)">7</div>
            <div class="mid-high" onclick="setResposta(8)">8</div>
            <div class="high" onclick="setResposta(9)">9</div>
            <div class="high" onclick="setResposta(10)">10</div>
        </div>

        <button id="botao-proxima" disabled class="btn btn-primary mt-3 w-100">Próxima</button>

    </div>

    <footer class="footer">
        <p>Sua avaliação espontânea é anônima, nenhuma informação pessoal é solicitada ou armazenada.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
