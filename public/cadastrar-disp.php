<?php
require_once '../src/db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identificador = $_POST['identificador'];
    $setor_id = $_POST['setor_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO dispositivos (identificador, setor_id) VALUES (:identificador, :setor_id)");
        $stmt->execute([
            ':identificador' => $identificador,
            ':setor_id' => $setor_id
        ]);
        echo "<p class='text-success'>Dispositivo cadastrado com sucesso!</p>";
    } catch (PDOException $e) {
        echo "<p class='text-danger'>Erro ao cadastrar dispositivo: " . $e->getMessage() . "</p>";
    }
}

try {
    $setores = $pdo->query("SELECT id, nome FROM setores")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("<p class='text-danger'>Erro ao buscar setores: " . $e->getMessage() . "</p>");
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
    <div class="container mt-5">
        <h1>Cadastro de Dispositivos</h1>
        
        <form method="POST">
            <div class="mb-3">
                <label for="identificador" class="form-label">Identificador do Dispositivo:</label>
                <input type="text" name="identificador" id="identificador" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label for="setor_id" class="form-label">Setor:</label>
                <select name="setor_id" id="setor_id" class="form-select" required>
                    <option value="">Selecione um setor</option>
                    <?php foreach ($setores as $setor): ?>
                        <option value="<?= htmlspecialchars($setor['id']) ?>"><?= htmlspecialchars($setor['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>

        <a href="index.php" class="btn btn-secondary mt-3">Voltar</a>
    </div>
</body>
</html>
