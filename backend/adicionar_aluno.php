<?php
require_once 'conexao.php';

// Verifica se o ID da turma foi passado
if (!isset($_GET['turma_id']) || !is_numeric($_GET['turma_id'])) {
    die("ID da turma inválido!");
}

$turma_id = $_GET['turma_id'];

try {
    // Busca informações da turma
    $stmt_turma = $pdo->prepare("SELECT * FROM tb_turmas WHERE id_turmas = ?");
    $stmt_turma->execute([$turma_id]);
    $turma = $stmt_turma->fetch();
    
    if (!$turma) {
        die("Turma não encontrada!");
    }

    // Busca alunos já cadastrados na turma
    $stmt_alunos_turma = $pdo->prepare("
        SELECT a.id_alunos, a.nome, at.numero_chamada, at.data_matricula, 
               at.total_presencas, at.total_faltas, at.percentual_presenca
        FROM tb_alunos a
        INNER JOIN tb_alunos_turma at ON a.id_alunos = at.id_aluno
        WHERE at.id_turma = ?
        ORDER BY at.numero_chamada
    ");
    $stmt_alunos_turma->execute([$turma_id]);
    $alunos_na_turma = $stmt_alunos_turma->fetchAll();

    // Busca todos os alunos disponíveis (que não estão na turma)
    $stmt_alunos_disponiveis = $pdo->prepare("
        SELECT id_alunos, nome 
        FROM tb_alunos 
        WHERE id_alunos NOT IN (
            SELECT id_aluno FROM tb_alunos_turma WHERE id_turma = ?
        )
        ORDER BY nome
    ");
    $stmt_alunos_disponiveis->execute([$turma_id]);
    $alunos_disponiveis = $stmt_alunos_disponiveis->fetchAll();

    // Processa o formulário quando enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alunos'])) {
        try {
            $pdo->beginTransaction();
            
            // Encontra o próximo número de chamada disponível
            $stmt_max_chamada = $pdo->prepare("
                SELECT COALESCE(MAX(numero_chamada), 0) + 1 
                FROM tb_alunos_turma 
                WHERE id_turma = ?
            ");
            $stmt_max_chamada->execute([$turma_id]);
            $proximo_numero = $stmt_max_chamada->fetchColumn();
            
            foreach ($_POST['alunos'] as $aluno_id) {
                $stmt = $pdo->prepare("
                    INSERT INTO tb_alunos_turma 
                    (id_aluno, id_turma, numero_chamada, data_matricula, 
                     total_presencas, total_faltas, percentual_presenca) 
                    VALUES (?, ?, ?, CURRENT_DATE(), 0, 0, 100.00)
                ");
                $stmt->execute([
                    $aluno_id,
                    $turma_id,
                    $proximo_numero++
                ]);
            }
            
            $pdo->commit();
            $success = "Aluno(s) adicionado(s) com sucesso!";
            
            // Atualiza as listas após adição
            $stmt_alunos_disponiveis->execute([$turma_id]);
            $alunos_disponiveis = $stmt_alunos_disponiveis->fetchAll();
            
            $stmt_alunos_turma->execute([$turma_id]);
            $alunos_na_turma = $stmt_alunos_turma->fetchAll();
            
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = "Erro ao adicionar aluno(s): " . $e->getMessage();
            
            // Verifica se é erro de violação de chave única (número de chamada)
            if ($e->getCode() == 23000) {
                $error .= "<br>Ocorreu um conflito com os números de chamada. Tente novamente.";
            }
        }
    }

} catch (PDOException $e) {
    die("Erro ao buscar dados: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Aluno à Turma</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 1000px; margin: 0 auto; }
        .success { color: green; margin: 15px 0; }
        .error { color: red; margin: 15px 0; }
        .info-box { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 8px 15px; border-radius: 4px; text-decoration: none; display: inline-block; }
        .btn-primary { background: #4CAF50; color: white; border: none; }
        .btn-back { background: #6c757d; color: white; margin-bottom: 20px; }
        .actions { margin-top: 20px; }
        .presenca-cell { text-align: center; }
        .good-presence { color: green; }
        .bad-presence { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <a href="listar_turmas.php" class="btn btn-back">Voltar para Lista de Turmas</a>
        
        <h1>Adicionar Aluno à Turma</h1>
        
        <div class="info-box">
            <h3>Informações da Turma</h3>
            <p><strong>Nome:</strong> <?= htmlspecialchars($turma['nome_turma']) ?></p>
            <p><strong>Tipo:</strong> <?= $turma['tipo_turma'] == 'pre_vestibular' ? 'Pré-Vestibular' : 'Regular' ?></p>
            <p><strong>Período:</strong> 
                <?= match($turma['periodo']) {
                    'manha' => 'Manhã',
                    'tarde' => 'Tarde',
                    'noite' => 'Noite',
                    default => $turma['periodo']
                } ?>
            </p>
            <p><strong>Alunos na turma:</strong> <?= count($alunos_na_turma) ?> / <?= $turma['numero_alunos'] ?></p>
        </div>
        
        <?php if (isset($success)): ?>
            <div class="success"><?= $success ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if (count($alunos_disponiveis) > 0): ?>
            <form method="post">
                <h3>Alunos Disponíveis para Adição</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Selecionar</th>
                            <th>ID</th>
                            <th>Nome</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alunos_disponiveis as $aluno): ?>
                        <tr>
                            <td><input type="checkbox" name="alunos[]" value="<?= $aluno['id_alunos'] ?>"></td>
                            <td><?= $aluno['id_alunos'] ?></td>
                            <td><?= htmlspecialchars($aluno['nome']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="actions">
                    <button type="submit" class="btn btn-primary">Adicionar Aluno(s) Selecionado(s)</button>
                </div>
            </form>
        <?php else: ?>
            <p>Não há alunos disponíveis para adicionar a esta turma.</p>
        <?php endif; ?>
        
        <?php if (!empty($alunos_na_turma)): ?>
            <h3>Alunos Matriculados na Turma</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nº Chamada</th>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Data Matrícula</th>
                        <th>Presenças</th>
                        <th>Faltas</th>
                        <th>% Presença</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alunos_na_turma as $aluno): ?>
                    <tr>
                        <td><?= $aluno['numero_chamada'] ?></td>
                        <td><?= $aluno['id_alunos'] ?></td>
                        <td><?= htmlspecialchars($aluno['nome']) ?></td>
                        <td><?= date('d/m/Y', strtotime($aluno['data_matricula'])) ?></td>
                        <td class="presenca-cell"><?= $aluno['total_presencas'] ?></td>
                        <td class="presenca-cell"><?= $aluno['total_faltas'] ?></td>
                        <td class="presenca-cell <?= $aluno['percentual_presenca'] >= 75 ? 'good-presence' : 'bad-presence' ?>">
                            <?= number_format($aluno['percentual_presenca'], 2) ?>%
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>