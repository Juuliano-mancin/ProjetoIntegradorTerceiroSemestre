<?php
header('Content-Type: application/json');
require_once 'conexao.php';

$mostrarInativos = isset($_GET['todos']) && $_GET['todos'] == 1;

try {
    $sql = "SELECT * FROM tb_alunos";
    
    if (!$mostrarInativos) {
        $sql .= " WHERE status = 'ativo'";
    }
    
    $sql .= " ORDER BY nome, sobrenome";
    
    $stmt = $pdo->query($sql);
    $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($alunos);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao consultar alunos: ' . $e->getMessage()]);
}