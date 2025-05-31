<?php
header('Content-Type: application/json');

require_once 'conexao.php';

try {
    $stmt = $pdo->query("
        SELECT 
            id_alunos,
            CASE 
                WHEN nome_social IS NOT NULL AND nome_social != '' 
                THEN nome_social 
                ELSE nome 
            END AS nome,
            sobrenome, 
            telefone,
            matricula
        FROM tb_alunos
    ");

    $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($alunos);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao consultar dados: ' . $e->getMessage()]);
}
