<?php
require 'conexao.php';

$id_turma = $_POST['id_turma'] ?? null;
$data = $_POST['data'] ?? date('Y-m-d');
$presencas = $_POST['presenca'] ?? [];

if (!$id_turma) {
  die("Turma inválida.");
}

// Obter todos os alunos da turma
$stmt = $pdo->prepare("SELECT id_aluno FROM tb_alunos_turma WHERE id_turma = ?");
$stmt->execute([$id_turma]);
$alunos = $stmt->fetchAll(PDO::FETCH_COLUMN);

foreach ($alunos as $id_aluno) {
  $situacao = isset($presencas[$id_aluno]) ? 'presente' : 'ausente';

  // Verifica se já existe chamada nesse dia para o aluno
  $check = $pdo->prepare("SELECT id_chamada FROM tb_chamada WHERE id_aluno = ? AND id_turma = ? AND data_chamada = ?");
  $check->execute([$id_aluno, $id_turma, $data]);

  if ($check->rowCount()) {
    // Atualiza status
    $update = $pdo->prepare("UPDATE tb_chamada SET situacao = ? WHERE id_aluno = ? AND id_turma = ? AND data_chamada = ?");
    $update->execute([$situacao, $id_aluno, $id_turma, $data]);
  } else {
    // Insere nova chamada
    $insert = $pdo->prepare("INSERT INTO tb_chamada (id_aluno, id_turma, data_chamada, situacao) VALUES (?, ?, ?, ?)");
    $insert->execute([$id_aluno, $id_turma, $data, $situacao]);
  }
}

echo "Chamada salva com sucesso!";
