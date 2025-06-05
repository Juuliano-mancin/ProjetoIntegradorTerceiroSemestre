<?php
require_once 'conexao.php';

try {
    $stmt = $pdo->query("SELECT * FROM tb_turmas ORDER BY data_criacao DESC");
    $turmas = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erro ao listar turmas: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Turmas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 1000px; margin: 0 auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .actions a { margin-right: 5px; text-decoration: none; }
        .btn { padding: 5px 10px; border-radius: 3px; }
        .btn-primary { background: #4CAF50; color: white; }
        .btn-danger { background: #f44336; color: white; }
        .success { color: green; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Lista de Turmas Cadastradas</h1>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="success">Turma cadastrada com sucesso!</div>
        <?php endif; ?>
        
        <a href="cadastro_turma.php" class="btn btn-primary">Nova Turma</a>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome da Turma</th>
                    <th>Tipo</th>
                    <th>Período</th>
                    <th>Data Criação</th>
                    <th>Máx. Alunos</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($turmas as $turma): ?>
                <tr>
                    <td><?= $turma['id_turmas'] ?></td>
                    <td><?= htmlspecialchars($turma['nome_turma']) ?></td>
                    <td><?= $turma['tipo_turma'] == 'pre_vestibular' ? 'Pré-Vestibular' : 'Regular' ?></td>
                    <td>
                        <?= match($turma['periodo']) {
                            'manha' => 'Manhã',
                            'tarde' => 'Tarde',
                            'noite' => 'Noite',
                            default => $turma['periodo']
                        } ?>
                    </td>
                    <td><?= date('d/m/Y', strtotime($turma['data_criacao'])) ?></td>
                    <td><?= $turma['numero_alunos'] ?></td>
                    <td class="actions">
                        <a href="editar_turma.php?id=<?= $turma['id_turmas'] ?>" class="btn btn-primary">Editar</a>
                        <a href="excluir_turma.php?id=<?= $turma['id_turmas'] ?>" class="btn btn-danger" onclick="return confirm('Tem certeza?')">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>