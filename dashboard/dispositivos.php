<?php
require_once '../src/db.php'; 

if (!$pdo) {
    die("Erro: Não foi possível conectar ao banco de dados.");
}

try {
    $query = $pdo->query("SELECT id, nome, setor_id FROM dispositivos");
    $dispositivos = $query->fetchAll();
} catch (PDOException $e) {
    die("Erro ao consultar dispositivos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Dispositivos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link active" href="dispositivos.php">Dispositivos</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-4">
    <h1 class="text-center mb-4 text-primary">Cadastro de Dispositivos</h1>

    <form method="POST" action="salvar-dispositivo.php">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Dispositivo</label>
            <input type="text" id="nome" name="nome" class="form-control" required>
        </div>
        <div class="mb-3">
    <label for="setor" class="form-label">Setor</labe>
            <select id="setor" name="setor_id" class="form-select" required>
             <option value="">Selecione um setor</option>
             <option value="1">Recepção</optio>
             <option value="2">Emergência</option>
             <option value="3">Enfermaria</option>
             <option value="4">Alimentação</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>

    <h2 class="mt-4">Dispositivos Cadastrados</h2>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Setor</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dispositivos as $dispositivo): ?>
                <tr>
                    <td><?= htmlspecialchars($dispositivo['id']) ?></td>
                    <td><?= htmlspecialchars($dispositivo['nome']) ?></td>
                    <td><?= htmlspecialchars($dispositivo['setor_id']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>