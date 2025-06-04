<?php
header('Content-Type: application/json');
require_once 'conexao.php';

// Verifica se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido']);
    exit;
}

// Recebe os dados do formulário
$dados = json_decode(file_get_contents('php://input'), true);
if (!$dados) {
    $dados = $_POST;
}

// Validação básica
if (!isset($dados['id_alunos'])) {
    http_response_code(400);
    echo json_encode(['erro' => 'ID do aluno não informado']);
    exit;
}

try {
    // Prepara a query de atualização
    $stmt = $pdo->prepare("
        UPDATE tb_alunos SET
            nome = :nome,
            nome_social = :nome_social,
            sobrenome = :sobrenome,
            genero = :genero,
            nascimento = :nascimento,
            telefone = :telefone,
            email = :email,
            status = :status
        WHERE id_alunos = :id
    ");
    
    // Executa a atualização
    $stmt->execute([
        ':nome' => $dados['nome'],
        ':nome_social' => !empty($dados['nome_social']) ? $dados['nome_social'] : null,
        ':sobrenome' => $dados['sobrenome'],
        ':genero' => $dados['genero'],
        ':nascimento' => $dados['nascimento'],
        ':telefone' => !empty($dados['telefone']) ? $dados['telefone'] : null,
        ':email' => !empty($dados['email']) ? $dados['email'] : null,
        ':status' => $dados['status'],
        ':id' => $dados['id_alunos']
    ]);
    
    // Verifica se alguma linha foi afetada
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