<?php
header('Content-Type: application/json');
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido']);
    exit;
}

$dados = json_decode(file_get_contents('php://input'), true);
if (!$dados) {
    $dados = $_POST;
}

if (!isset($dados['id_alunos'])) {
    http_response_code(400);
    echo json_encode(['erro' => 'ID do aluno não informado']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        UPDATE tb_alunos SET
            nome = :nome,
            nome_social = :nome_social,
            sobrenome = :sobrenome,
            genero = :genero,
            telefone = :telefone,
            email = :email,
            cont_emergencia_nome = :cont_emergencia_nome,
            cont_emergencia_relacao = :cont_emergencia_relacao,
            cont_emergencia_contato = :cont_emergencia_contato
        WHERE id_alunos = :id
    ");
    
    $stmt->execute([
        ':nome' => $dados['nome'],
        ':nome_social' => !empty($dados['nome_social']) ? $dados['nome_social'] : null,
        ':sobrenome' => $dados['sobrenome'],
        ':genero' => $dados['genero'],
        ':telefone' => !empty($dados['telefone']) ? $dados['telefone'] : null,
        ':email' => !empty($dados['email']) ? $dados['email'] : null,
        ':cont_emergencia_nome' => !empty($dados['cont_emergencia_nome']) ? $dados['cont_emergencia_nome'] : null,
        ':cont_emergencia_relacao' => $dados['cont_emergencia_relacao'] ?? 'nenhum',
        ':cont_emergencia_contato' => !empty($dados['cont_emergencia_contato']) ? $dados['cont_emergencia_contato'] : null,
        ':id' => $dados['id_alunos']
    ]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['sucesso' => 'Dados do aluno atualizados com sucesso']);
    } else {
        http_response_code(404);
        echo json_encode(['erro' => 'Nenhum dado foi alterado ou aluno não encontrado']);
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao atualizar aluno: ' . $e->getMessage()]);
}