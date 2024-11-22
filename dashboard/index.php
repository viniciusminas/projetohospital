<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Avaliações</title>
    
    <!-- Bibliotecas utilizadas -->
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/localforage/1.10.0/localforage.min.js"></script>
</head>
<body>
    <!-- Login Container -->
    <div id="loginContainer" class="container py-5">
        <h2 class="text-center">Login</h2>
        <form id="loginForm">
            <div class="mb-3">
                <label for="username" class="form-label">Usuário</label>
                <input type="text" class="form-control" id="username" placeholder="Digite seu usuário">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" class="form-control" id="password" placeholder="Digite sua senha">
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
        <p id="loginMessage" class="text-danger text-center mt-3"></p>
    </div>

    <!-- Dashboard Container -->
    <div id="dashboardContainer" class="container py-4" style="display: none;">
        <h1 class="text-center mb-4">Dashboard - Avaliações</h1>
        <div class="row mb-4">
            <div class="col-md-4">
                <select id="setor" class="form-select">
                    <option value="">Selecione um setor</option>
                    <option value="Recepção">Recepção</option>
                    <option value="Emergência">Emergência</option>
                    <option value="Enfermaria">Enfermaria</option>
                    <option value="Alimentação">Alimentação</option>
                </select>
            </div>
            <div class="col-md-2">
                <button id="atualizar" class="btn btn-primary w-100">Atualizar</button>
            </div>
        </div>

        <canvas id="graficoAvaliacoes" height="100"></canvas>

        <!-- Tabela -->
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>Pergunta</th>
                    <th>Setor</th>
                    <th>Dispositivo</th>
                    <th>Média da Nota</th>
                    <th>Total de Respostas</th>
                </tr>
            </thead>
            <tbody id="tabelaAvaliacoes"></tbody>
        </table>
    </div>

    <!-- Scripts -->
    <script src="js/login.js"></script>
    <script src="js/dashboard.js"></script>
</body>
</html>
