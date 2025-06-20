<?php
header('Content-Type: application/json');
require_once 'conexao.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['erro' => 'ID inválido']);
    exit;
}

$id = (int)$_GET['id'];

try {
    // Buscar informações do aluno
    $stmt = $pdo->prepare("
        SELECT * FROM tb_alunos 
        WHERE id_alunos = :id
    ");
    $stmt->execute([':id' => $id]);
    $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$aluno) {
        http_response_code(404);
        echo json_encode(['erro' => 'Aluno não encontrado']);
        exit;
    }

    echo json_encode($aluno);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao consultar dados: ' . $e->getMessage()]);
}