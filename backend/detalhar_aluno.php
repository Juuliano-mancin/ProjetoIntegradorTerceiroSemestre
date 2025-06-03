<?php
header('Content-Type: application/json');
require_once 'conexao.php';

// Verifica se o ID foi enviado pela URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['erro' => 'ID do aluno não fornecido ou inválido']);
    exit;
}

$id = intval($_GET['id']);

try {
    // Consulta os dados do aluno pelo ID
    $stmt = $pdo->prepare("SELECT * FROM tb_alunos WHERE id_alunos = ?");
    $stmt->execute([$id]);
    $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$aluno) {
        echo json_encode(['erro' => 'Aluno não encontrado']);
        exit;
    }

    echo json_encode($aluno);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao consultar o banco de dados: ' . $e->getMessage()]);
}
?>
