<?php
header('Content-Type: application/json');
require_once 'conexao.php';

// Verifica se há erros de sintaxe antes de executar
try {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new Exception("Método não permitido", 405);
    }

    // Verifica se o conteúdo é JSON ou form-data
    $input = file_get_contents('php://input');
    if (strpos($input, '{') === 0) {
        $dados = json_decode($input, true);
    } else {
        $dados = $_POST;
    }

    // Validação dos dados
    if (empty($dados['nome_turma'])) {
        throw new Exception("O nome da turma é obrigatório.", 400);
    }

    if (empty($dados['tipo_turma']) || !in_array($dados['tipo_turma'], ['pre_vestibular', 'vestibulinho'])) {
        throw new Exception("Tipo de turma inválido.", 400);
    }

    if (empty($dados['periodo']) || !in_array($dados['periodo'], ['manha', 'tarde', 'noite'])) {
        throw new Exception("Período inválido.", 400);
    }

    if (empty($dados['data_criacao'])) {
        throw new Exception("A data de criação é obrigatória.", 400);
    }

    // Verifica a data
    $dataCriacao = DateTime::createFromFormat('Y-m-d', $dados['data_criacao']);
    if (!$dataCriacao || $dataCriacao->format('Y-m-d') !== $dados['data_criacao']) {
        throw new Exception("Data de criação inválida.", 400);
    }

    if ($dataCriacao > new DateTime()) {
        throw new Exception("A data de criação não pode ser no futuro.", 400);
    }

    // Prepara a query
    $sql = "INSERT INTO tb_turmas (nome_turma, tipo_turma, periodo, data_criacao) 
            VALUES (:nome_turma, :tipo_turma, :periodo, :data_criacao)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome_turma' => $dados['nome_turma'],
        ':tipo_turma' => $dados['tipo_turma'],
        ':periodo' => $dados['periodo'],
        ':data_criacao' => $dados['data_criacao']
    ]);

    // Verifica se inseriu corretamente
    if ($stmt->rowCount() === 0) {
        throw new Exception("Nenhuma turma foi cadastrada.", 500);
    }

    // Resposta de sucesso
    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Turma cadastrada com sucesso!',
        'id_turma' => $pdo->lastInsertId()
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'erro' => 'Erro no banco de dados: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 400);
    echo json_encode([
        'sucesso' => false,
        'erro' => $e->getMessage()
    ]);
}

// Garante que nenhum output extra será enviado
exit;