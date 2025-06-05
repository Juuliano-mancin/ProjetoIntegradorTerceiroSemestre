<?php
require_once 'conexao.php';

// Processamento do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_turma = filter_input(INPUT_POST, 'nome_turma', FILTER_SANITIZE_STRING);
    $tipo_turma = filter_input(INPUT_POST, 'tipo_turma', FILTER_SANITIZE_STRING);
    $periodo = filter_input(INPUT_POST, 'periodo', FILTER_SANITIZE_STRING);
    $data_criacao = filter_input(INPUT_POST, 'data_criacao', FILTER_SANITIZE_STRING);
    $numero_alunos = filter_input(INPUT_POST, 'numero_alunos', FILTER_SANITIZE_NUMBER_INT);

    try {
        $stmt = $pdo->prepare("INSERT INTO tb_turmas (nome_turma, tipo_turma, periodo, data_criacao, numero_alunos) 
                              VALUES (:nome_turma, :tipo_turma, :periodo, :data_criacao, :numero_alunos)");
        
        $stmt->execute([
            ':nome_turma' => $nome_turma,
            ':tipo_turma' => $tipo_turma,
            ':periodo' => $periodo,
            ':data_criacao' => $data_criacao,
            ':numero_alunos' => $numero_alunos
        ]);

        header("Location: listar_turmas.php?success=1");
        exit();
    } catch (PDOException $e) {
        $error = "Erro ao cadastrar turma: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Turmas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 600px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #4CAF50; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #45a049; }
        .error { color: red; margin-bottom: 15px; }
        .success { color: green; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cadastro de Turmas</h1>
        
        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="nome_turma">Nome da Turma:</label>
                <input type="text" id="nome_turma" name="nome_turma" required>
            </div>
            
            <div class="form-group">
                <label for="tipo_turma">Tipo da Turma:</label>
                <select id="tipo_turma" name="tipo_turma" required>
                    <option value="">Selecione...</option>
                    <option value="pre_vestibular">Pré-Vestibular</option>
                    <option value="regular">Regular</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="periodo">Período:</label>
                <select id="periodo" name="periodo" required>
                    <option value="">Selecione...</option>
                    <option value="manha">Manhã</option>
                    <option value="tarde">Tarde</option>
                    <option value="noite">Noite</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="data_criacao">Data de Criação:</label>
                <input type="date" id="data_criacao" name="data_criacao" required>
            </div>
            
            <div class="form-group">
                <label for="numero_alunos">Número Máximo de Alunos:</label>
                <input type="number" id="numero_alunos" name="numero_alunos" min="1" required>
            </div>
            
            <button type="submit">Cadastrar Turma</button>
        </form>
    </div>
</body>
</html>