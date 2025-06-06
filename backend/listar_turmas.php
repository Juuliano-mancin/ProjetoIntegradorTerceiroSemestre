<?php
header('Content-Type: application/json');
require_once 'conexao.php';

try {
    $mostrarTodos = isset($_GET['todos']) && $_GET['todos'] == '1';
    
    $sql = "
        SELECT 
            t.id_turmas,
            t.nome_turma,
            t.tipo_turma,
            t.periodo,
            t.data_criacao,
            t.numero_alunos,
            t.status,
            COUNT(at.id_aluno) as total_alunos
        FROM tb_turmas t
        LEFT JOIN tb_alunos_turma at ON t.id_turmas = at.id_turma
    ";
    
    if (!$mostrarTodos) {
        $sql .= " WHERE t.status = 'ativo'";
    }
    
    $sql .= " GROUP BY t.id_turmas ORDER BY t.nome_turma";
    
    $stmt = $pdo->query($sql);
    $turmas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($turmas);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao consultar turmas: ' . $e->getMessage()]);
}