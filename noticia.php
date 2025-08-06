<?php
session_start();

include_once "./config/config.php";
include_once "./config/conexao.php";

//Traz a classe que esta todas as config
$tudo = new Tudo();

// Validação do ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("<script>alert('ID da notícia não encontrado.'); window.location.href='index.php';</script>");
}
//Busca o id da noticia
$id = intval($_GET['id']);
$row = $tudo->buscarId($id);

// Verifica se a notícia existe
if (!$row) {
    die("<script>alert('Notícia não encontrada!'); window.location.href='index.php';</script>");
}

?>
<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($row['titulo']) ?> | Seleção Pública</title>
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
    <header>
        <header class="navbar">
            <div class="nav-container">
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
                <a href="../index.php">Início</a>
                <?php 

                if (isset($_SESSION['adm']) && $_SESSION['adm'] === "admin"): ?>
                    <a href="../admin/gerenciarNoticias.php">Gerenciar Notícias</a>
                    <a href="../admin/addNoticia.php">Adicionar Notícia</a>
                    <a href="../admin/cadastrarAdmin.php">Cadastrar admin</a>
                    <a href="../logout.php">Sair</a>
                <?php else: ?>
                    <a href="../login.php">Login</a>
                <?php endif; ?>
            </div>

            <!-- Menu lateral mobile -->
            <div class="sidebar" id="sidebar">
                <?php
                echo ' <a href="../index.php">Início</a>';
                if (isset($_SESSION['adm']) && $_SESSION['adm'] === "admin") {
                    echo "<a href='../admin/gerenciarNoticias.php'>Gerenciar Notícias</a>";
                    echo "<a href='../admin/addNoticia.php'>Adicionar Notícia</a>";
                    echo '<a href="../admin/cadastrarAdmin.php">Cadastrar admin</a>';
                    echo '<a href="../logout.php">Sair</a>';
                } else {
                    echo '<a href="../login.php">Login</a>';
                }
                ?>
            </div>
            <div class="overlay" id="overlay"></div>
            </div>
            </div>
            <div class="search-box" id="search-box">
                <input type="text" placeholder="Pesquisar..." />
            </div>
            </nav>
        </header>
        <div class="nav-gradient-bar"></div>

        <main class="main">
            <div class="container">
                <article class="article">

                    <header class="article-header">
                        <h1 class="article-title"><?= htmlspecialchars($row['titulo']) ?></h1>
                        <div class="article-meta">
                            <?php if (!empty($row['data_publicacao'])): ?>
                                <div class="article-date">
                                    <time datetime="<?= htmlspecialchars($row['data_publicacao']) ?>">
                                        Data Da Publicação: <?= date('d/m/Y', strtotime($row['data_publicacao'])) ?>
                                    </time>
                                </div>
                            <?php else: ?>
                                <div class="article-date">
                                    <em>Data não disponível</em>
                                </div>
                            <?php endif; ?>

                        </div>
                    </header>

                    <div class="article-image">
                        <img src="../imagens/<?= htmlspecialchars($row['imagem']) ?>" alt="Imagem da Notícia"
                            class="featured-image" />
                    </div>

                    <div class="article-content">
                        <p class="lead"><?= nl2br(htmlspecialchars($row['conteudo'])) ?></p>
                    </div>

                    <?php if (!empty($row['conteudo2'])): ?>
                        <div class="article-content">
                            <p class="lead"><?= nl2br(htmlspecialchars($row['conteudo2'])) ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($row['pdf'])): ?>
                        <div class="article-content">
                            <p><strong>PDF:</strong>
                                <a href="../pdf/<?= htmlspecialchars($row['pdf']) ?>" target="_blank">
                                    Abrir PDF
                                </a>
                            </p>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($row['link_externo'])): ?>
                        <div class="article-content">
                            <p><strong>Link Externo:</strong>
                                <a href="<?= htmlspecialchars($row['link_externo']) ?>" target="_blank"
                                    rel="noopener noreferrer">
                                    <?= htmlspecialchars($row['link_externo']) ?>
                                </a>
                            </p>
                        </div>
                    <?php endif; ?>
                </article>

                <div class="footer-bottom">
                    <p>&copy; 2025 Portal News. Todos os direitos reservados.</p>
                </div>
            </div>
        </main>
        <script src="../script.js"></script>
</body>

</html>