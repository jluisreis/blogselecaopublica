<?php
require_once '../config/conexao.php';
require_once '../config/config.php';

session_start();

if (!isset($_SESSION['adm']) || $_SESSION['adm'] !== "admin") {
  header("Location: ../index.php");
  exit;
}

$conexao = new Conexao();
$db = $conexao->connect();
$noticia = new Tudo($db);

if (isset($_POST['excluir'])) {
  $id = intval($_POST['excluir']);

  $dados = $noticia->buscarId($id); // para deletar arquivos
  if ($dados && $noticia->deletarNoticia($id)) {
    if (!empty($dados['imagem']) && file_exists("../imagens/" . $dados['imagem'])) {
      unlink("../imagens/" . $dados['imagem']);
    }
    if (!empty($dados['pdf']) && file_exists("../pdf/" . $dados['pdf'])) {
      unlink("../pdf/" . $dados['pdf']);
    }

    header("Location: gerenciarNoticias.php");
    exit;
  } else {
    $erro = "Erro ao excluir a notícia.";
  }
}

$noticias = $noticia->verNoticia();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Seleção Pública - Admin</title>
  <link rel="stylesheet" href="../css/styl.css" />
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
          <?php if (isset($_SESSION['adm']) && $_SESSION['adm'] === "admin"): ?>
            <a href="../index.php">Início</a>
            <a href="gerenciarNoticias.php">Gerenciar Notícias</a>
            <a href="addNoticia.php">Adicionar Notícia</a>
            <a href="cadastrarAdmin.php">Cadastrar admin</a>
            <a href="../logout.php">Sair</a>
          <?php else: ?>
            <a href="login.php">Login</a>
          <?php endif; ?>
        </div>
      </div>

      <!-- Menu lateral mobile -->
      <div class="sidebar" id="sidebar">
        <?php
        if (isset($_SESSION['adm']) && $_SESSION['adm'] === "admin") {
          echo ' <a href="../index.php">Início</a>';
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
    <div class="nav-gradient-bar"></div>
  </header>

  <main class="main">
    <div class="container">
      <div class="page-header">
        <h1>Gerenciar Notícias</h1>
        <p>Visualize, edite e exclua suas notícias</p>
      </div>

      <div class="news-management">
        <?php if (count($noticias) > 0): ?>
          <?php foreach ($noticias as $n): ?>
            <div class="news-item">
              <img src="../imagens/<?= htmlspecialchars($n['imagem']) ?>" alt="<?= htmlspecialchars($n['slug']) ?>"
                class="news-image">
              <div class="news-content">
                <h3 class="news-title"><?= htmlspecialchars($n['titulo']) ?></h3>
                <p class="news-description">
                  <?= htmlspecialchars(mb_strimwidth($n['conteudo'], 0, 110, '...')) ?>
                </p>
                <div class="news-meta">
                  <div class="news-date"> <?= date('d/m/Y', strtotime($n['data_publicacao'])) ?></div>
                </div>
              </div>
              <div class="news-actions">
                <a href="editar-noticia.php?id=<?= $n['id'] ?>" class="btn btn-edit">Editar</a>

                <!-- Form para exclusão sem JS -->
                <form method="POST" style="display:inline;"
                  onsubmit="return confirm('Confirma exclusão da notícia: <?= addslashes(htmlspecialchars($n['slug'])) ?>?');">
                  <input type="hidden" name="excluir" value="<?= $n['id'] ?>">
                  <button type="submit" class="btn btn-delete">Excluir</button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>Nenhuma notícia cadastrada.</p>
        <?php endif; ?>
      </div>

      <div class="footer-bottom">
        <p>&copy; 2025 Seleção Pública. Todos os direitos reservados.</p>
      </div>
    </div>
  </main>
  <script src="../script.js"></script>
</body>

</html>