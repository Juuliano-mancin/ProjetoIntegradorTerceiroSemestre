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
    // Buscar informações básicas da turma
    $stmt = $pdo->prepare("
        SELECT * FROM tb_turmas 
        WHERE id_turmas = :id
    ");
    $stmt->execute([':id' => $id]);
    $turma = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$turma) {
        http_response_code(404);
        echo json_encode(['erro' => 'Turma não encontrada']);
        exit;
    }

    // Buscar alunos matriculados na turma
    $stmt = $pdo->prepare("
        SELECT 
            a.id_alunos,
            a.nome,
            a.nome_social,
            a.sobrenome,
            a.matricula,
            at.numero_chamada,
            at.total_presencas,
            at.total_faltas,
            at.percentual_presenca
        FROM tb_alunos_turma at
        JOIN tb_alunos a ON at.id_aluno = a.id_alunos
        WHERE at.id_turma = :id
        ORDER BY at.numero_chamada
    ");
    $stmt->execute([':id' => $id]);
    $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $turma['alunos'] = $alunos;
    echo json_encode($turma);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao consultar dados: ' . $e->getMessage()]);
}