<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../img/logo.png" type="image/png">
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div id="loginContainer">
            <h2 class="text-primary">Administrativo - HRAV</h2>
            <p class="text-muted">Faça login para acessar o sistema</p>
            <form id="loginForm">
                <div class="mb-3">
                    <label for="username" class="form-label">Usuário</label>
                    <input type="text" class="form-control shadow-sm" id="username" name="usuario" placeholder="Digite seu usuário">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" class="form-control shadow-sm" id="password" name="senha" placeholder="Digite sua senha">
                </div>
                <button type="submit" class="btn btn-primary w-100 shadow-sm">Entrar</button>
            </form>
            <p id="loginMessage" class="text-danger text-center mt-3"></p>
        </div>
    </div>
    <script src="js/login.js"></script>
</body>
</html>
