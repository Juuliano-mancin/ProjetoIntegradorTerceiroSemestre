<?php
header('Content-Type: application/json');

// Inclui o arquivo de conexão
require_once 'conexao.php'; // Certifique-se de que o caminho está correto

try {
    // Consulta os dados da tabela tb_alunos
    $stmt = $pdo->query("
        SELECT 
            nome, 
            sobrenome, 
            genero, 
            DATE_FORMAT(nascimento, '%d/%m/%Y') AS data_nascimento,
            cpf,
            matricula,
            email,
            cidade,
            uf,
            status
        FROM tb_alunos
    ");

    $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($alunos);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao consultar dados: ' . $e->getMessage()]);
}