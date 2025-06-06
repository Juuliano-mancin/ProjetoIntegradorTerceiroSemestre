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
        SELECT 
            at.id_aluno,
            CONCAT(a.nome, ' ', a.sobrenome) as nome_completo,
            at.numero_chamada
        FROM tb_alunos_turma at
        JOIN tb_alunos a ON at.id_aluno = a.id_alunos
        WHERE at.id_turma = :id_turma
        ORDER BY at.numero_chamada
    ");
    $stmt->execute([':id_turma' => $idTurma]);
    $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($alunos);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao listar alunos: ' . $e->getMessage()]);
}