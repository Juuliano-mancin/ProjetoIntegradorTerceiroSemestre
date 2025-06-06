<?php
// matricular_aluno.php
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
    // 1. Verificar se o aluno já está matriculado na turma
    $stmt = $pdo->prepare("SELECT id_aluno_turma FROM tb_alunos_turma WHERE id_aluno = ? AND id_turma = ?");
    $stmt->execute([$input['id_aluno'], $input['id_turma']]);

    if ($stmt->rowCount() > 0) {
        http_response_code(400);
        echo json_encode(['erro' => 'Aluno já está matriculado nesta turma.']);
        exit;
    }

    // 2. Gerar número de chamada automaticamente (próximo número disponível)
    if (empty($input['numero_chamada'])) {
        $stmt = $pdo->prepare("
            SELECT COALESCE(MAX(numero_chamada), 0) + 1 as proximo_numero
            FROM tb_alunos_turma
            WHERE id_turma = ?
        ");
        $stmt->execute([$input['id_turma']]);
        $proximoNumero = $stmt->fetch()['proximo_numero'];
        $input['numero_chamada'] = $proximoNumero;
    } else {
        // Verificar se o número de chamada já está em uso para esta turma
        $stmt = $pdo->prepare("SELECT id_aluno_turma FROM tb_alunos_turma WHERE id_turma = ? AND numero_chamada = ?");
        $stmt->execute([$input['id_turma'], $input['numero_chamada']]);

        if ($stmt->rowCount() > 0) {
            http_response_code(400);
            echo json_encode(['erro' => 'Número de chamada já está em uso para esta turma.']);
            exit;
        }
    }

    // 3. Realizar a matrícula
    $stmt = $pdo->prepare("
        INSERT INTO tb_alunos_turma (
            id_aluno,
            id_turma,
            numero_chamada,
            data_matricula
        ) VALUES (
            :id_aluno,
            :id_turma,
            :numero_chamada,
            NOW()
        )
    ");
    $stmt->execute([
        ':id_aluno' => $input['id_aluno'],
        ':id_turma' => $input['id_turma'],
        ':numero_chamada' => $input['numero_chamada']
    ]);

    // 4. Atualizar contador de alunos na turma
    // This is the query that was suspected. We wrap it in its own try-catch for precise error catching.
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
        // Log this specific error, but allow the main matriculation to succeed if this is non-critical
        error_log("PDO Warning/Error on updating numero_alunos (matricular_aluno.php): " . $e->getMessage() . " SQLSTATE: " . $e->getCode());
        // Do NOT exit here, as the main matriculation was successful.
        // You might consider adding a flag or a different message for the client if this update fails.
    }

    echo json_encode(['mensagem' => 'Aluno matriculado com sucesso!']);

} catch (PDOException $e) {
    // Log the general PDO error for the matriculation process
    error_log("PDO Error in matricular_aluno.php: " . $e->getMessage() . " SQLSTATE: " . $e->getCode());
    http_response_code(500);
    // Return a more user-friendly message, but include the SQLSTATE for debugging the actual problem
    echo json_encode(['erro' => 'Erro ao matricular aluno: ' . $e->getMessage() . ' (Código do Erro: ' . $e->getCode() . ')']);
} catch (Exception $e) {
    // Catch any other unexpected errors
    error_log("General Error in matricular_aluno.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['erro' => 'Ocorreu um erro inesperado: ' . $e->getMessage()]);
}
?>