<?php
require_once 'conexao.php';

if (!isset($_GET['id'])) {
    header("Location: listar_turmas.php");
    exit();
}

$id_turma = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

// Buscar dados da turma
try {
    $stmt = $pdo->prepare("SELECT * FROM tb_turmas WHERE id_turmas = ?");
    $stmt->execute([$id_turma]);
    $turma = $stmt->fetch();
    
    if (!$turma) {
        header("Location: listar_turmas.php");
        exit();
    }
} catch (PDOException $e) {
    die("Erro ao buscar turma: " . $e->getMessage());
}

// Processamento do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_turma = filter_input(INPUT_POST, 'nome_turma', FILTER_SANITIZE_STRING);
    $tipo_turma = filter_input(INPUT_POST, 'tipo_turma', FILTER_SANITIZE_STRING);
    $periodo = filter_input(INPUT_POST, 'periodo', FILTER_SANITIZE_STRING);
    $data_criacao = filter_input(INPUT_POST, 'data_criacao', FILTER_SANITIZE_STRING);
    $numero_alunos = filter_input(INPUT_POST, 'numero_alunos', FILTER_SANITIZE_NUMBER_INT);

    try {
        $stmt = $pdo->prepare("UPDATE tb_turmas SET 
                              nome_turma = :nome_turma, 
                              tipo_turma = :tipo_turma, 
                              periodo = :periodo, 
                              data_criacao = :data_criacao, 
                              numero_alunos = :numero_alunos 
                              WHERE id_turmas = :id");
        
        $stmt->execute([
            ':nome_turma' => $nome_turma,
            ':tipo_turma' => $tipo_turma,
            ':periodo' => $periodo,
            ':data_criacao' => $data_criacao,
            ':numero_alunos' => $numero_alunos,
            ':id' => $id_turma
        ]);

        header("Location: listar_turmas.php?success=1");
        exit();
    } catch (PDOException $e) {
        $error = "Erro ao atualizar turma: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Turma</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 600px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #4CAF50; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #45a049; }
        .error { color: red; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Turma</h1>
        
        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="nome_turma">Nome da Turma:</label>
                <input type="text" id="nome_turma" name="nome_turma" value="<?= htmlspecialchars($turma['nome_turma']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="tipo_turma">Tipo da Turma:</label>
                <select id="tipo_turma" name="tipo_turma" required>
                    <option value="pre_vestibular" <?= $turma['tipo_turma'] == 'pre_vestibular' ? 'selected' : '' ?>>Pré-Vestibular</option>
                    <option value="regular" <?= $turma['tipo_turma'] == 'regular' ? 'selected' : '' ?>>Regular</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="periodo">Período:</label>
                <select id="periodo" name="periodo" required>
                    <option value="manha" <?= $turma['periodo'] == 'manha' ? 'selected' : '' ?>>Manhã</option>
                    <option value="tarde" <?= $turma['periodo'] == 'tarde' ? 'selected' : '' ?>>Tarde</option>
                    <option value="noite" <?= $turma['periodo'] == 'noite' ? 'selected' : '' ?>>Noite</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="data_criacao">Data de Criação:</label>
                <input type="date" id="data_criacao" name="data_criacao" value="<?= $turma['data_criacao'] ?>" required>
            </div>
            
            <div class="form-group">
                <label for="numero_alunos">Número Máximo de Alunos:</label>
                <input type="number" id="numero_alunos" name="numero_alunos" min="1" value="<?= $turma['numero_alunos'] ?>" required>
            </div>
            
            <button type="submit">Atualizar Turma</button>
            <a href="listar_turmas.php" style="margin-left: 10px;">Cancelar</a>
        </form>
    </div>
</body>
</html>