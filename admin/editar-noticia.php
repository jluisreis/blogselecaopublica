<?php
include_once "../config/conexao.php";
include_once "../config/config.php";
$tudo = new Tudo;

session_start();

if (!isset($_SESSION['adm']) || $_SESSION['adm'] !== "admin") {
    header("Location: ../index.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("ID não informado.");
}

$id = intval($_GET['id']);
$buscar = $tudo->buscarId($id);

if (!$buscar) {
    die("<script>alert('Notícia não encontrada!'); window.location='gerenciar-noticias.php';</script>");
}

if (isset($_POST['publicar'])) {
    $slug = $_POST['slug'];
    $titulo = $_POST['titulo'];
    $conteudo = $_POST['conteudo'];
    $data_publicacao = $_POST['data'];
    $link = $_POST['link'];

    $dirImg = "../imagens/";
    $dirPdf = "../pdf/";

    if (!is_dir($dirImg)) mkdir($dirImg, 0777, true);
    if (!is_dir($dirPdf)) mkdir($dirPdf, 0777, true);

    $imagem = $buscar['imagem'];
    if (!empty($_FILES['image']['name'])) {
        $imagem = uniqid() . "-" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $dirImg . $imagem);
    }

    $pdf = $buscar['pdf'];
    if (!empty($_FILES['pdf']['name'])) {
        $pdf = uniqid() . "-" . $_FILES['pdf']['name'];
        move_uploaded_file($_FILES['pdf']['tmp_name'], $dirPdf . $pdf);
    }

    $dados = [
        'slug' => $slug,
        'titulo' => $titulo,
        'data_publicacao' => $data_publicacao,
        'conteudo' => $conteudo,
        'link' => $link,
        'pdf' => $pdf
    ];

    if ($tudo->editarNoticia($id, $dados, $imagem, $pdf)) {
        echo "<script>alert('Notícia atualizada com sucesso!'); window.location='gerenciarNoticias.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar notícia.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Seleção Pública - Admin</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<header>
  <nav class="navbar">
    <div class="nav-container">
      <h1 class = "blue" class="logo"><span style = "color:white;">Seleção</span> Pública - Admin</h1>
                <div class="nav-links">
                    <a href="../index.php">Início</a>
          <?php
          if (isset($_SESSION['adm']) && $_SESSION['adm'] === "admin") {
            echo "<a href='../admin/addNoticia.php'>Adicionar Notícia</a>";
            echo '<a href="../admin/cadastrarAdmin.php">Cadastrar admin</a>';
            echo "<a href='../admin/gerenciarNoticias.php'>Gerenciar Notícias</a>";
            echo '<a href="logout.php">Sair</a>';
          } else {
            echo '<a href="../login.php">Login</a>';
          }
          ?>
                </div>
    </div>
  </nav>
</header>

<main>
  <section class="admin-container">
    <div class="admin-form">
      <h2>Editar Notícia</h2>
      <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label for="slug">Título (Slug):</label>
          <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($buscar['slug']) ?>" required>
        </div>

        <div class="form-group">
          <label for="titulo">Título real (completo):</label>
          <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($buscar['titulo']) ?>" required>
        </div>

        <div class="form-group">
          <label for="conteudo">Conteúdo:</label>
          <textarea id="conteudo" name="conteudo" rows="4" required><?= htmlspecialchars($buscar['conteudo']) ?></textarea>
        </div>

        <div class="form-group">
          <label for="data">Data de Publicação:</label>
          <input type="date" id="data" name="data" value="<?= htmlspecialchars($buscar['data_publicacao']) ?>" required>
        </div>
        
        <div class="form-group">
          <label>Imagem Atual:</label><br>
          <?php if ($buscar['imagem']): ?>
            <img src="../imagens/<?= $buscar['imagem'] ?>" alt="Imagem atual" width="150"><br>
          <?php else: ?>
            Nenhuma imagem enviada.<br>
          <?php endif; ?>
          <input type="file" name="image" accept="image/*">
        </div>
        
        <div class="form-group">
      <label>PDF Atual:</label><br>
      <?php if (!empty($buscar['pdf'])): ?>
        <a href="../pdf/<?= htmlspecialchars($buscar['pdf']) ?>" target="_blank">Ver PDF atual</a><br>
      <?php else: ?>
        Nenhum PDF enviado.<br>
      <?php endif; ?>
      <input type="file" name="pdf" accept="/pdf">
        </div>

        

        <div class="form-group">
          <label for="link">Link Externo:</label>
          <input type="url" id="link" name="link" value="<?= htmlspecialchars($buscar['link_externo']) ?>">
        </div>

        <button type="submit" name="publicar" class="btn btn-primary">Salvar Alterações</button>
      </form>
    </div>
  </section>
</main>
</body>
</html>
