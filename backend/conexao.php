<?php
/**
 * Arquivo de conexão com o banco de dados
 * 
 * Configura a conexão com o banco de dados db_gestao_escolar
 */

class Conexao {
    // Configurações do banco de dados
    private $host = 'localhost';
    private $dbname = 'db_gestao_escolar';
    private $username = 'root'; // Altere para seu usuário do MySQL
    private $password = ''; // Altere para sua senha do MySQL
    private $charset = 'utf8mb4';
    
    // Objeto PDO
    private $pdo;
    
    // Mensagem de erro
    private $error;
    
    public function __construct() {
        // DSN (Data Source Name)
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
        
        // Opções do PDO
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        
        try {
            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            error_log("Erro de conexão: " . $this->error);
            die("Erro ao conectar ao banco de dados. Por favor, tente novamente mais tarde.");
        }
    }
    
    /**
     * Retorna a conexão PDO
     * @return PDO
     */
    public function getPdo() {
        return $this->pdo;
    }
    
    /**
     * Retorna o último erro ocorrido
     * @return string
     */
    public function getError() {
        return $this->error;
    }
}

// Cria uma instância da conexão para ser usada globalmente
try {
    $conexao = new Conexao();
    $pdo = $conexao->getPdo();
    
    // Define o timezone para evitar problemas com datas
    $pdo->exec("SET time_zone = '-03:00'");
} catch (Exception $e) {
    error_log("Erro ao inicializar conexão: " . $e->getMessage());
    die("Erro no sistema. Por favor, contate o administrador.");
}
?>