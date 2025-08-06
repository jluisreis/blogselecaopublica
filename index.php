<?php
session_start();
include_once "config/config.php";

$tudo = new Tudo;
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Seleção Pública</title>
  <link rel="stylesheet" href="./css/styles.css" />
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
    <section class="hero">
      <h2>Notícias</h2>
      <p>Seleção Diretores e Coordenadores</p>
    </section>

    <section class="news-container">
      <?php
      $mostra = $tudo->verNoticia();
      foreach ($mostra as $mostrar):
        ?>
        <div class="card">
          <img src="./imagens/<?= htmlspecialchars($mostrar['imagem']) ?>" alt="" />
          <div class="card-content">
            <h3 class="title"><?= nl2br(htmlspecialchars(mb_strimwidth($mostrar['titulo'], 0, 125, '...'))) ?></h3>
            <p class="description">
              <?= nl2br(htmlspecialchars(mb_strimwidth($mostrar['conteudo'], 0, 80, '...'))) ?>
            </p>

            <p class="date"> Data Da Publicação:
              <?= (new DateTime($mostrar['data_publicacao']))->format('d/m/Y') ?>
            </p>
            <?php
            $slug_com_hifen = str_replace(' ', '-', $mostrar['slug']);
            $url = "noticias/" . rawurlencode($slug_com_hifen) . "-" . $mostrar['id'] . ".php";
            ?>
            <a href="<?= $url ?>">Ler Mais</a>

          </div>
        </div>
      <?php endforeach; ?>
    </section>
  </main>
  <footer>
    <p>&copy; 2025 Portal de Notícias. Todos os direitos reservados.</p>
  </footer>
  <script src="script.js"></script>
</body>

</html>