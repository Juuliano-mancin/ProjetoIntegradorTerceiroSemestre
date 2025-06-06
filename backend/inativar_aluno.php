<?php
header('Content-Type: application/json');
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido']);
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    http_response_code(400);
    echo json_encode(['erro' => 'ID do aluno inválido']);
    exit;
}

try {
    // Verificar se o aluno existe
    $stmt = $pdo->prepare("SELECT id_alunos FROM tb_alunos WHERE id_alunos = :id");
    $stmt->execute([':id' => $id]);
    
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(['erro' => 'Aluno não encontrado']);
        exit;
    }

    // Atualizar status
    $stmt = $pdo->prepare("UPDATE tb_alunos SET status = 'inativo' WHERE id_alunos = :id");
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['mensagem' => 'Aluno inativado com sucesso']);
    } else {
        http_response_code(400);
        echo json_encode(['erro' => 'O aluno já está inativo']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao inativar aluno: ' . $e->getMessage()]);
}