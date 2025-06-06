<?php
// remover_aluno_turma.php
header('Content-Type: application/json');
require_once 'conexao.php'; // Ensure this path is correct

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

// Validações básicas
if (empty($input['id_aluno']) || empty($input['id_turma'])) {
    http_response_code(400);
    echo json_encode(['erro' => 'Dados incompletos (ID do aluno ou ID da turma ausentes).']);
    exit;
}

try {
    // 1. Verificar se o aluno está matriculado na turma
    $stmt = $pdo->prepare("SELECT id_aluno_turma FROM tb_alunos_turma WHERE id_aluno = ? AND id_turma = ?");
    $stmt->execute([$input['id_aluno'], $input['id_turma']]);

    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(['erro' => 'Aluno não está matriculado nesta turma.']);
        exit;
    }

    // 2. Remover o aluno da turma
    $stmt = $pdo->prepare("DELETE FROM tb_alunos_turma WHERE id_aluno = ? AND id_turma = ?");
    $stmt->execute([$input['id_aluno'], $input['id_turma']]);

    // 3. Atualizar contador de alunos na turma
    // Again, wrap this in its own try-catch for precise error catching.
    try {
        $stmt = $pdo->prepare("
            UPDATE tb_turmas
            SET numero_alunos = (
                SELECT COUNT(*)
                FROM tb_alunos_turma
                WHERE id_turma = :id_turma
            )
            WHERE id_turmas = :id_turma
        ");
        $stmt->execute([':id_turma' => $input['id_turma']]);
    } catch (PDOException $e) {
        // Log this specific error, but allow the main removal to succeed if this is non-critical
        error_log("PDO Warning/Error on updating numero_alunos (remover_aluno_turma.php): " . $e->getMessage() . " SQLSTATE: " . $e->getCode());
        // Do NOT exit here, as the main removal was successful.
    }

    echo json_encode(['mensagem' => 'Aluno removido da turma com sucesso!']);

} catch (PDOException $e) {
    // Log the general PDO error for the removal process
    error_log("PDO Error in remover_aluno_turma.php: " . $e->getMessage() . " SQLSTATE: " . $e->getCode());
    http_response_code(500);
    // Return a more user-friendly message, but include the SQLSTATE for debugging the actual problem
    echo json_encode(['erro' => 'Erro ao remover aluno: ' . $e->getMessage() . ' (Código do Erro: ' . $e->getCode() . ')']);
} catch (Exception $e) {
    // Catch any other unexpected errors
    error_log("General Error in remover_aluno_turma.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['erro' => 'Ocorreu um erro inesperado: ' . $e->getMessage()]);
}
?>