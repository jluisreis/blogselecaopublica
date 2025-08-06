<?php 

class Conexao {
    private $servidor = "localhost";
    private $usuario = "u251111997_kayro";
    private $senha = '&N[>S2CeJi8j';
    private $banco = "u251111997_blog";

    public function connect() {
        try {
            $conn = new PDO(
                "mysql:host={$this->servidor};dbname={$this->banco};charset=utf8",
                $this->usuario,
                $this->senha
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            echo "Erro na conexão com banco: " . $e->getMessage();
            return null;
        }
    }
}
?>
