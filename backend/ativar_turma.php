<?php
header('Content-Type: application/json');
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    
    if (!$id) {
        http_response_code(400);
        echo json_encode(['erro' => 'ID inválido']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE tb_turmas SET status = 'ativo' WHERE id_turmas = :id");
        $stmt->execute([':id' => $id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['mensagem' => 'Turma ativada com sucesso']);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Nenhuma turma foi ativada ou turma não encontrada']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['erro' => 'Erro ao ativar turma: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido']);
}