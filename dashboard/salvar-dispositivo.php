<?php
require_once '../src/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $setor = $_POST['setor_id'] ?? '';
    $status = $_POST['status'] ?? 'ativo';  // 'ativo' será tratado como true e 'inativo' como false

    // Atribuindo valores booleanos
    $statusBoolean = ($status === 'ativo') ? true : false;

    if (!empty($nome) && !empty($setor)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO dispositivos (nome, setor_id, status) VALUES (:nome, :setor_id, :status)");
            $stmt->execute(['nome' => $nome, 'setor_id' => $setor, 'status' => $statusBoolean]);

            // Redirecionar após sucesso
            header("Location: dispositivos.php");
            exit;
        } catch (PDOException $e) {
            die("Erro ao salvar dispositivo: " . $e->getMessage());
        }
    } else {
        echo "Todos os campos são obrigatórios.";
    }
}
?>
