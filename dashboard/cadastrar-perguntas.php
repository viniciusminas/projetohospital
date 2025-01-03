<?php
require_once '../src/db.php';

$conexao = conectarBanco();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['pergunta'], $_POST['setor'])) {
        $pergunta = $_POST['pergunta'];
        $setor = $_POST['setor'];

        try {
            $stmt = $conexao->prepare("INSERT INTO perguntas (texto, id_setor, status) VALUES (:texto, :setor, TRUE)");
            $stmt->execute([':texto' => $pergunta, ':setor' => $setor]);

            echo "<script>alert('Pergunta cadastrada com sucesso!');</script>";
        } catch (PDOException $e) {
            die("Erro ao inserir pergunta: " . $e->getMessage());
        }
    } elseif (isset($_POST['id'], $_POST['pergunta'])) {
        $id = $_POST['id'];
        $pergunta = $_POST['pergunta'];

        try {
            $stmt = $conexao->prepare("UPDATE perguntas SET texto = :texto WHERE id = :id");
            $stmt->execute([':texto' => $pergunta, ':id' => $id]);

            echo "<script>alert('Pergunta editada com sucesso!');</script>";
        } catch (PDOException $e) {
            die("Erro ao editar pergunta: " . $e->getMessage());
        }
    }
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    try {
        $stmt = $conexao->prepare("DELETE FROM perguntas WHERE id = :id");
        $stmt->execute([':id' => $id]);

        echo json_encode(['status' => 'success']);
        exit();
    } catch (PDOException $e) {
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

    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Perguntas Cadastradas</h3>
            <!-- Filtro para Setor -->
            <form method="GET" action="" class="d-flex align-items-center">
                <label for="filtroSetor" class="form-label mb-0 me-2">Filtrar:</label>
                <select id="filtroSetor" name="setor" class="form-select form-select-sm me-2 w-auto" style="min-width: 150px;">
                <option value="">Todos os Setores</option>
                    <?php
                    // Preencher opções de setor para o filtro
                    $stmt = $conexao->query("SELECT id, nome FROM setores");
                    while ($setor = $stmt->fetch()) {
                        echo "<option value='{$setor['id']}'" . (isset($_GET['setor']) && $_GET['setor'] == $setor['id'] ? ' selected' : '') . ">{$setor['nome']}</option>";
                    }
                    ?>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
            </form>
        </div>

        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Setor</th>
                    <th>Pergunta</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //filtro de setor
                $setorFiltro = isset($_GET['setor']) ? (int) $_GET['setor'] : null;


                $sql = "SELECT perguntas.id, perguntas.texto, perguntas.status, setores.nome AS setor_nome 
                        FROM perguntas
                        LEFT JOIN setores ON perguntas.id_setor = setores.id";

                if ($setorFiltro) {
                    $sql .= " WHERE perguntas.id_setor = :setor";
                }

                $stmt = $conexao->prepare($sql);

                if ($setorFiltro) {
                    $stmt->bindParam(':setor', $setorFiltro, PDO::PARAM_INT);
                }

                $stmt->execute();

                while ($row = $stmt->fetch()) {
                    echo "<tr id='pergunta-{$row['id']}'>
                            <td>{$row['id']}</td>
                            <td>{$row['setor_nome']}</td>
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

<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditarLabel">Editar Pergunta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="editarId" name="id"> 
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

function editarPergunta(id, texto) {
    document.getElementById('editarId').value = id; 
    document.getElementById('editarPergunta').value = texto; 
    new bootstrap.Modal(document.getElementById('modalEditar')).show(); 
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault(); 

            var perguntaId = this.getAttribute('data-id');

            if (confirm("Tem certeza que deseja excluir esta pergunta?")) {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', '?delete=' + perguntaId, true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);

                        if (response.status === 'success') {
                            var row = btn.closest('tr');
                            row.remove();
                            

                            alert('Pergunta removida com sucesso!');
                        } else {
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
