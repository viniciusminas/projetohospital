<?php
require_once '../src/db.php'; // Importa a conexão com o banco de dados

// Conexão com o banco
$conexao = conectarBanco();

// Manipulação de dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['pergunta'], $_POST['setor'])) {
        // Inserção de nova pergunta
        $pergunta = $_POST['pergunta'];
        $setor = $_POST['setor'];

        try {
            $stmt = $conexao->prepare("INSERT INTO perguntas (texto, status) VALUES (:texto, TRUE)");
            $stmt->execute([':texto' => $pergunta]);

            // Mensagem de sucesso
            echo "<script>alert('Pergunta cadastrada com sucesso!');</script>";
        } catch (PDOException $e) {
            die("Erro ao inserir pergunta: " . $e->getMessage());
        }
    } elseif (isset($_POST['id'], $_POST['pergunta'])) {
        // Edição de pergunta existente
        $id = $_POST['id'];
        $pergunta = $_POST['pergunta'];

        try {
            $stmt = $conexao->prepare("UPDATE perguntas SET texto = :texto WHERE id = :id");
            $stmt->execute([':texto' => $pergunta, ':id' => $id]);

            // Mensagem de sucesso
            echo "<script>alert('Pergunta editada com sucesso!');</script>";
        } catch (PDOException $e) {
            die("Erro ao editar pergunta: " . $e->getMessage());
        }
    }
}

// Remoção de perguntas (AJAX)
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    try {
        // Remover pergunta do banco
        $stmt = $conexao->prepare("DELETE FROM perguntas WHERE id = :id");
        $stmt->execute([':id' => $id]);

        // Retorna um JSON com o status de sucesso
        echo json_encode(['status' => 'success']);
        exit();
    } catch (PDOException $e) {
        // Retorna um JSON com o status de erro
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Perguntas</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Hospital</a>
    </div>
</nav>
<div class="container py-4">
    <h1 class="text-center mb-4 text-primary">Cadastro de Perguntas</h1>
    <!-- Formulário -->
    <div class="card p-4 mb-4">
        <form id="formPerguntas" method="POST" action="">
            <div class="mb-3">
                <label for="pergunta" class="form-label">Pergunta</label>
                <input type="text" class="form-control" id="pergunta" placeholder="Digite aqui para criar uma pergunta" name="pergunta" required>
            </div>
            <div class="mb-3">
                <label for="setor" class="form-label">Setor</label>
                <select id="setor" name="setor" class="form-select" required>
                    <?php
                    // Preencher opções de setor
                    $stmt = $conexao->query("SELECT id, nome FROM setores");
                    while ($setor = $stmt->fetch()) {
                        echo "<option value='{$setor['id']}'>{$setor['nome']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Salvar Pergunta</button>
        </form>
    </div>

    <!-- Tabela -->
    <div class="card p-4">
        <h3 class="mb-3">Perguntas Cadastradas</h3>
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Pergunta</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Listar perguntas
                $stmt = $conexao->query("SELECT * FROM perguntas");
                while ($row = $stmt->fetch()) {
                    echo "<tr id='pergunta-{$row['id']}'>
                        <td>{$row['id']}</td>
                        <td>{$row['texto']}</td>
                        <td>" . ($row['status'] ? 'Ativo' : 'Inativo') . "</td>
                        <td>
                            <a href='#' class='btn btn-danger btn-sm delete-btn' data-id='{$row['id']}'>Excluir</a>
                            <button class='btn btn-warning btn-sm' onclick=\"editarPergunta({$row['id']}, '{$row['texto']}')\">Editar</button>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal de Edição -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditarLabel">Editar Pergunta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="editarId" name="id"> <!-- Campo oculto para o ID -->
          <div class="mb-3">
            <label for="editarPergunta" class="form-label">Pergunta</label>
            <input type="text" class="form-control" id="editarPergunta" name="pergunta" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary" name="acao" value="editar">Salvar Alterações</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Função para editar pergunta
function editarPergunta(id, texto) {
    document.getElementById('editarId').value = id; // Preenche o ID oculto
    document.getElementById('editarPergunta').value = texto; // Preenche o campo de texto da pergunta
    new bootstrap.Modal(document.getElementById('modalEditar')).show(); // Abre o modal
}

// Função para excluir pergunta com AJAX
document.addEventListener('DOMContentLoaded', function() {
    // Adicionar evento para cada botão de exclusão
    document.querySelectorAll('.delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault(); // Evitar o comportamento padrão de navegação

            var perguntaId = this.getAttribute('data-id');

            // Confirmação de exclusão
            if (confirm("Tem certeza que deseja excluir esta pergunta?")) {
                // Fazer uma requisição AJAX para excluir
                var xhr = new XMLHttpRequest();
                xhr.open('GET', '?delete=' + perguntaId, true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);

                        if (response.status === 'success') {
                            // Remover a linha da tabela
                            var row = btn.closest('tr');
                            row.remove();
                            
                            // Exibir o alerta de sucesso
                            alert('Pergunta removida com sucesso!');
                        } else {
                            // Caso ocorra algum erro
                            alert('Erro ao remover a pergunta: ' + response.message);
                        }
                    }
                };
                xhr.send();
            }
        });
    });
});
</script>

</body>
</html>
