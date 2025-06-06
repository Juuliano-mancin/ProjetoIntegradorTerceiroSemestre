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
    // Buscar informações básicas do aluno
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

    // Buscar turmas do aluno
    $stmt = $pdo->prepare("
        SELECT 
            t.nome_turma,
            t.tipo_turma,
            t.periodo,
            at.numero_chamada,
            at.data_matricula,
            at.total_presencas,
            at.total_faltas,
            at.percentual_presenca
        FROM tb_alunos_turma at
        JOIN tb_turmas t ON at.id_turma = t.id_turmas
        WHERE at.id_aluno = :id
    ");
    $stmt->execute([':id' => $id]);
    $turmas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $aluno['turmas'] = $turmas;
    echo json_encode($aluno);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao consultar dados: ' . $e->getMessage()]);
}