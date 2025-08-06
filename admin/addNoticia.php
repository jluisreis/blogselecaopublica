<?php
// Inclui as classes
require_once '../config/conexao.php';
require_once '../config/config.php';

session_start();

if (!isset($_SESSION['adm']) || $_SESSION['adm'] !== "admin") {
    header("Location: ../index.php");
    exit;
}

// Função para gerar slug sem acentos
function gerarSlug($str) {
    // Deixa tudo minúsculo
    $str = mb_strtolower($str, 'UTF-8');
    
    // Translitera caracteres acentuados para ASCII (remove acentos)
    $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
    
    // Remove tudo que não for letra, número, espaço ou hífen
    $str = preg_replace('/[^a-z0-9\s-]/', '', $str);
    
    // Substitui espaços e hífens repetidos por um único hífen
    $str = preg_replace('/[\s-]+/', '-', $str);
    
    // Remove hífen no começo ou fim
    return trim($str, '-');
}


// Processa o formulário se enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['publicar'])) {
    $slugOriginal = $_POST['slug'];
    $slug = gerarSlug($slugOriginal);

    $titulo = $_POST['titulo'];
    $data_publicacao = $_POST['data'];
    $conteudo = $_POST['conteudo'];
    $link = $_POST['link'];

    $dirImagem = "../imagens/";
    $dirPdf = "../pdf/";

    if (!is_dir($dirImagem)) mkdir($dirImagem, 0777, true);
    if (!is_dir($dirPdf)) mkdir($dirPdf, 0777, true);

    $imagem = "";
    if (!empty($_FILES['image']['name'])) {
        $imagem = uniqid() . "-" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $dirImagem . $imagem);
    }

    $pdf = "";
    if (!empty($_FILES['pdf']['name'])) {
        $pdf = uniqid() . "-" . basename($_FILES['pdf']['name']);
        move_uploaded_file($_FILES['pdf']['tmp_name'], $dirPdf . $pdf);
    }

    $conexao = new Conexao();
    $db = $conexao->connect();
    $noticia = new Tudo();

    $dados = [
        'slug' => $slug,
        'titulo' => $titulo,
        'data_publicacao' => $data_publicacao,
        'conteudo' => $conteudo,
        'link' => $link,
        'pdf' => $pdf
    ];

    if ($noticia->cadastrarNoticia($dados, $imagem, $pdf)) {
        echo "<script>alert('Notícia publicada com sucesso!'); window.location='addNoticia.php';</script>";
    } else {
        echo "<script>alert('Erro ao publicar a notícia.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administração - Portal de Notícias</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-header">
                    <button id="menu-toggle" class="menu-toggle" aria-label="Menu">
                        <span class="bar"></span>
                        <span class="bar"></span>
                        <span class="bar"></span>
                    </button>
                    <h1 class="logo blue title-centered">
                        <span style="color:white;">Seleção</span> Pública
                    </h1>
                    <button id="search-toggle" class="search-toggle" aria-label="Pesquisar">🔍</button>
                </div>
                <div class="nav-links-desktop">
                    <a href="../index.php">Início</a>
                    <a href="gerenciarNoticias.php">Gerenciar Notícias</a>
                    <a href="addNoticia.php">Adicionar Notícia</a>
                    <a href="cadastrarAdmin.php">Cadastrar admin</a>
                    <a href="../logout.php">Sair</a>
                </div>
            </div>
        </nav>
        <div class="nav-gradient-bar"></div>
    </header>
    <main>
        <section class="admin-container">
            <div class="admin-form">
                <h2>Cadastrar Nova Notícia</h2>
                <form id="newsForm" action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">Título da Notícia(URL):</label>
                        <input type="text" id="title" name="slug" required>
                    </div>

                    <div class="form-group">
                        <label for="title">Titulo Oficial:</label>
                        <input type="text" id="title" name="titulo" required>
                    </div>
                    <div class="form-group">
                        <label for="title">Data:</label>
                        <input type="date" id="data" name="data" required>
                    </div>

                    <div class="form-group">
                        <label for="content">Conteúdo:</label>
                        <textarea id="content" name="conteudo" rows="10" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="image">Imagem (opcional):</label>
                        <input type="file" id="image" name="image" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label for="image">PDF (opcional):</label>
                        <input type="file" id="pdf" name="pdf" accept="application/pdf">
                    </div>

                    <div class="form-group">
                        <label for="link">Link Externo (opcional):</label>
                        <input type="url" id="link" name="link" placeholder="https://exemplo.com">
                    </div>

                    <button type="submit" name="publicar" class="btn btn-primary">Publicar Notícia</button>
                </form>
            </div>
        </section>
    </main>

    <div class="sidebar" id="sidebar">
        <a href="../index.php">Início</a>
        <a href="gerenciarNoticias.php">Gerenciar Notícias</a>
        <a href="addNoticia.php">Adicionar Notícia</a>
        <a href="cadastrarAdmin.php">Cadastrar admin</a>
        <a href="../logout.php">Sair</a>
    </div>
    <div class="overlay" id="overlay"></div>
    <div class="search-box" id="search-box">
        <input type="text" placeholder="Pesquisar..." />
    </div>
    <script src="../script.js"></script>
</body>

</html>
