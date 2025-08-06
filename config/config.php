<?php
include_once "conexao.php";

class Tudo
{
    protected $conn;

    public function __construct()
    {

        $conexao = new Conexao();
        $this->conn = $conexao->connect();
    }


    public function verNoticia()
    {
        $sql = "SELECT id,titulo, slug, conteudo, imagem, data_publicacao FROM noticias ORDER BY data_publicacao DESC    ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function autenticarDados($usuario, $senha)
    {
        if (empty($usuario) || empty($senha)) {
            echo "<script>alert('Preencha todos os campos!');</script>";
            return;
        }

        try {
            $sql = "SELECT * FROM admins WHERE usuario = :usuario LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':usuario', $usuario);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($senha === $admin['senha']) {
                    session_start();
                    $_SESSION['adm'] = "admin";
                    header("Location: index.php");
                    exit;
                } else {
                    echo "<script>alert('Senha incorreta!');</script>";
                }
            } else {
                echo "<script>alert('Usuário não encontrado!');</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('Erro ao autenticar')</script>";
        }
    }


    public function cadastrarUsuario($usuario, $senha)
    {
        if (empty($usuario) || empty($senha)) {
            echo "<script>alert('Preencha todos os campos!');</script>";
            return;
        }

        try {
            // Verificar se usuário já existe
            $sqlCheck = "SELECT COUNT(*) FROM admins WHERE usuario = :usuario";
            $stmtCheck = $this->conn->prepare($sqlCheck);
            $stmtCheck->bindParam(':usuario', $usuario);
            $stmtCheck->execute();

            $existe = $stmtCheck->fetchColumn();

            if ($existe > 0) {
                echo "<script>alert('Usuário já existe!');</script>";
                return;
            }

            // Inserir novo usuário
            $sqlInsert = "INSERT INTO admins (usuario, senha) VALUES (:usuario, :senha)";
            $stmtInsert = $this->conn->prepare($sqlInsert);
            $stmtInsert->bindParam(':usuario', $usuario);
            $stmtInsert->bindParam(':senha', $senha);
            $stmtInsert->execute();

            echo "<script>alert('Usuário cadastrado com sucesso!');</script>";
        } catch (PDOException $e) {
            echo "<script>alert('Erro ao cadastrar usuário.');</script>";
            return false;
        }
    }


    public function cadastrarNoticia($dados, $imagem, $pdf)
    {
        try {
            $sql = "INSERT INTO noticias
                    (slug, titulo, conteudo, conteudo2, data_publicacao , imagem, pdf, link_externo)
                    VALUES (:slug, :titulo, :conteudo, :conteudo2, :data_publicacao , :imagem, :pdf, :link_externo)";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':slug', $dados['slug']);
            $stmt->bindParam(':titulo', $dados['titulo']);
            $stmt->bindParam(':data_publicacao', $dados['data_publicacao']);
            $stmt->bindParam(':conteudo', $dados['conteudo']);
            $stmt->bindParam(':conteudo2', $dados['conteudo2']);
            $stmt->bindParam(':imagem', $imagem);
            $stmt->bindParam(':pdf', $pdf);
            $stmt->bindParam(':link_externo', $dados['link']);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "<script>alert('Erro ao cadastrar notícia')</script>";
            return false;
        }
    }


    public function editarNoticia($id, $dados, $imagem, $pdf)
    {
        try {
            $sql = "UPDATE noticias 
                SET titulo = :titulo, 
                    conteudo = :conteudo, 
                    data_publicacao = :data_publicacao, 
                    slug = :slug, 
                    conteudo2 = :conteudo2, 
                    imagem = :imagem, 
                    pdf = :pdf, 
                    link_externo = :link_externo 
                WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':titulo', $dados['titulo']);
            $stmt->bindParam(':data_publicacao', $dados['data_publicacao']);
            $stmt->bindParam(':slug', $dados['slug']);
            $stmt->bindParam(':conteudo', $dados['conteudo']);
            $stmt->bindParam(':conteudo2', $dados['conteudo2o']);
            $stmt->bindParam(':imagem', $imagem);
            $stmt->bindParam(':pdf', $pdf);
            $stmt->bindParam(':link_externo', $dados['link']);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "<script>alert('Erro ao editar notícia: " . addslashes($e->getMessage()) . "')</script>";
            return false;
        }
    }

    public function deletarNoticia($id)
    {
        try {
            $sql = "DELETE FROM noticias WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "<script>alert('Erro ao excluir notícia: " . addslashes($e->getMessage()) . "')</script>";
            return false;
        }
    }

    public function buscarId($id)
    {
        try {
            $sql = "SELECT * FROM noticias WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<script>alert('Erro ao buscar ID: " . addslashes($e->getMessage()) . "')</script>";
            return false;
        }
    }
}
