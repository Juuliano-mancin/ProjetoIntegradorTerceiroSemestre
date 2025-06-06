<?php
header('Content-Type: application/json');
require_once 'conexao.php';

$idTurma = filter_input(INPUT_GET, 'id_turma', FILTER_VALIDATE_INT);

if (!$idTurma) {
    http_response_code(400);
    echo json_encode(['erro' => 'ID da turma inválido']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT COALESCE(MAX(numero_chamada), 0) + 1 as proximo_numero
        FROM tb_alunos_turma
        WHERE id_turma = :id_turma
    ");
    $stmt->execute([':id_turma' => $idTurma]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($resultado);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao obter próximo número: ' . $e->getMessage()]);
}