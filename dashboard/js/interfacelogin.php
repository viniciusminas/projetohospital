<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/localforage/1.10.0/localforage.min.js"></script>
    <script src="../js/login.js" defer></script>
</head>
<body>
    <div id="loginContainer" class="container py-4" style="display: none;">
        <h1 class="text-center">Login</h1>
        <form id="loginForm" class="mt-4">
            <div class="mb-3">
                <label for="username" class="form-label">Usuário</label>
                <input type="text" id="username" class="form-control" placeholder="Digite seu usuário">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" id="password" class="form-control" placeholder="Digite sua senha">
            </div>
            <div id="loginMessage" class="text-danger"></div>
            <button type="submit" class="btn btn-primary w-100 mt-3">Entrar</button>
        </form>
    </div>

    <div id="dashboardContainer" class="container py-4" style="display: none;">
        <h1 class="text-center">Bem-vindo ao Dashboard</h1>
        <!-- conteudo do dashboard -->
    </div>
</body>
</html>
