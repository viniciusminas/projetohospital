<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Avaliações</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <link rel="icon" href="../img/logo.png" type="image/png">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Hospital</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cadastrar-perguntas.php" target="_blank">Configurações (Perguntas)</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Sair</a>
                    
                </li>
            </ul>
        </div>
    </div>
</nav>
    <div class="container py-4">
        <h1 class="text-center mb-4 text-primary">Dashboard - Avaliações</h1>
        <!-- Seletor de setor e botão -->
        <div class="row mb-4 d-flex align-items-center">
            <div class="col-md-6">
                <select id="setor" class="form-select shadow-sm">
                    <option value="">Selecione um setor</option>
                    <option value="Recepção">Recepção</option>
                    <option value="Emergência">Emergência</option>
                    <option value="Enfermaria">Enfermaria</option>
                    <option value="Alimentação">Alimentação</option>
                </select>
            </div>
            <div class="col-md-2">
                <button id="atualizar" class="btn btn-primary shadow-sm w-100">Atualizar</button>
            </div>
        </div>

        <!-- Gráfico -->
        <div class="p-4 bg-white rounded shadow">
            <canvas id="graficoDashboard" height="100"></canvas>
        </div>

        <!-- Tabela -->
        <table class="table table-hover mt-4 bg-white rounded shadow">
            <thead class="table-dark">
                <tr>
                    <th>Pergunta</th>
                    <th>Setor</th>
                    <th>Dispositivo</th>
                    <th>Média da Nota</th>
                    <th>Total de Respostas</th>
                </tr>
            </thead>
            <tbody id="tabelaDashboard"></tbody>
        </table>
    </div>

    <script src="js/dashboard.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>




