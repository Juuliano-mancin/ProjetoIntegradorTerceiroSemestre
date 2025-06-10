<?php
require 'conexao.php';

$stmt = $pdo->query("SELECT id_turmas, nome_turma FROM tb_turmas WHERE status = 'ativo' ORDER BY nome_turma");
$turmas = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($turmas);
?>
