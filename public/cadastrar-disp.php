<?php
require_once 'db.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identificador = $_POST['identificador'];
    $setor_id = $_POST['setor_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO dispositivos (identificador, setor_id) VALUES (:identificador, :setor_id)");
        $stmt->execute([
            ':identificador' => $identificador,
            ':setor_id' => $setor_id
        ]);
        echo "Dispositivo cadastrado com sucesso!";
    } catch (PDOException $e) {
        echo "Erro ao cadastrar dispositivo: " . $e->getMessage();
    }
}

// Busca os setores para o formulário
$setores = $pdo->query("SELECT id, nome FROM setores")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Dispositivos</title>
</head>
<body>
    <h1>Cadastro de Dispositivos</h1>
    <form method="POST">
        <label for="identificador">Identificador do Dispositivo:</label>
        <input type="text" name="identificador" id="identificador" required>
        
        <label for="setor_id">Setor:</label>
        <select name="setor_id" id="setor_id" required>
            <option value="">Selecione um setor</option>
            <?php foreach ($setores as $setor): ?>
                <option value="<?= $setor['id'] ?>"><?= $setor['nome'] ?></option>
            <?php endforeach; ?>
        </select>
        
        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>
