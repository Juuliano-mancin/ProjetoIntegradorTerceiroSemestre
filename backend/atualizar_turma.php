<?php
header('Content-Type: application/json');
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

// Validações básicas
if (empty($input['id_turmas'])) {
    http_response_code(400);
    echo json_encode(['erro' => 'ID da turma não informado']);
    exit;
}

if (empty($input['nome_turma'])) {
    http_response_code(400);
    echo json_encode(['erro' => 'Nome da turma é obrigatório']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        UPDATE tb_turmas SET
            nome_turma = :nome_turma,
            tipo_turma = :tipo_turma,
            periodo = :periodo,
            data_criacao = :data_criacao
        WHERE id_turmas = :id_turmas
    ");

    $stmt->execute([
        ':nome_turma' => $input['nome_turma'],
        ':tipo_turma' => $input['tipo_turma'],
        ':periodo' => $input['periodo'],
        ':data_criacao' => $input['data_criacao'],
        ':id_turmas' => $input['id_turmas']
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['mensagem' => 'Turma atualizada com sucesso']);
    } else {
        echo json_encode(['erro' => 'Nenhuma alteração realizada']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao atualizar turma: ' . $e->getMessage()]);
}