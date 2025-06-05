<?php
require_once 'conexao.php';

if (!isset($_GET['id'])) {
    header("Location: listar_turmas.php");
    exit();
}

$id_turma = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

try {
    // Verificar se há alunos matriculados na turma
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tb_alunos_turma WHERE id_turma = ?");
    $stmt->execute([$id_turma]);
    $alunos_matriculados = $stmt->fetchColumn();
    
    if ($alunos_matriculados > 0) {
        header("Location: listar_turmas.php?error=1");
        exit();
    }
    
    // Excluir a turma
    $stmt = $pdo->prepare("DELETE FROM tb_turmas WHERE id_turmas = ?");
    $stmt->execute([$id_turma]);
    
    header("Location: listar_turmas.php?success=2");
    exit();
} catch (PDOException $e) {
    die("Erro ao excluir turma: " . $e->getMessage());
}