<?php
session_start();
include_once "config/config.php";
$tudo = new Tudo;

if (isset($_POST['entrar'])) {
    $usuario = htmlspecialchars($_POST['usuario']);
    $senha = htmlspecialchars($_POST['senha']);

    $entrar = $tudo->autenticarDados($usuario, $senha);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Seleção Pública</title>
    <link rel="stylesheet" href="./css/styles.css">
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-header">
                    <!-- Botão ☰ -->
                    <button id="menu-toggle" class="menu-toggle" aria-label="Menu">
                        <span class="bar"></span>
                        <span class="bar"></span>
                        <span class="bar"></span>
                    </button>

                    <!-- Título -->
                    <h1 class="logo blue title-centered">
                        <span style="color:white;">Seleção</span> Pública
                    </h1>

                    <!-- Ícone de lupa -->
                    <button id="search-toggle" class="search-toggle" aria-label="Pesquisar">🔍</button>
                </div>

                <!-- Menu desktop -->
                <div class="nav-links-desktop">
                    <a href="index.php">Início</a>
                    <?php if (isset($_SESSION['adm']) && $_SESSION['adm'] === "admin"): ?>
                        <a href="./admin/gerenciarNoticias.php">Gerenciar Notícias</a>
                        <a href="./admin/addNoticia.php">Adicionar Notícia</a>
                        <a href="./admin/cadastrarAdmin.php">Cadastrar admin</a>
                        <a href="logout.php">Sair</a>
                    <?php else: ?>
                        <a href="login.php">Login</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Menu lateral mobile -->
            <div class="sidebar" id="sidebar">
                <a href="index.php">Início</a>
                <?php if (isset($_SESSION['adm']) && $_SESSION['adm'] === "admin"): ?>
                    <a href="./admin/gerenciarNoticias.php">Gerenciar Notícias</a>
                    <a href="./admin/addNoticia.php">Adicionar Notícia</a>
                    <a href="./admin/cadastrarAdmin.php">Cadastrar admin</a>
                    <a href="logout.php">Sair</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                <?php endif; ?>
            </div>
            <div class="overlay" id="overlay"></div>

            <!-- Campo de pesquisa flutuante -->
            <div class="search-box" id="search-box">
                <input type="text" placeholder="Pesquisar..." />
            </div>
        </nav>
        <!-- Barra degradê abaixo da navbar -->
        <div class="nav-gradient-bar"></div>
    </header>
    <main>
        <section class="login-container">
            <div class="login-form">
                <h2>Login do Administrador</h2>

                <form id="loginForm" action="login.php" method="POST">
                    <div class="form-group">
                        <label for="username">Usuário:</label>
                        <input type="text" id="username" name="usuario" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Senha:</label>
                        <input type="password" id="password" name="senha" required>
                    </div>

                    <button type="submit" name="entrar" class="btn btn-primary">Entrar</button>
                </form>

                <div id="login-message" class="message" style="display: none;"></div>
            </div>
        </section>
    </main>
    <script src="./script.js"></script>
</body>

</html>