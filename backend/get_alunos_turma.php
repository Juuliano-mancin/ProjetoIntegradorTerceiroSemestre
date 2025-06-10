<?php
require 'conexao.php';

$id_turma = $_GET['id_turma'] ?? 0;
$data = $_GET['data'] ?? date('Y-m-d');

$sql = "
  SELECT 
    a.id_alunos AS id_aluno, 
    CONCAT(COALESCE(a.nome_social, a.nome), ' ', a.sobrenome) AS nome,
    COALESCE(c.situacao, 'ausente') AS situacao
  FROM tb_alunos_turma at
  INNER JOIN tb_alunos a ON at.id_aluno = a.id_alunos
  LEFT JOIN tb_chamada c ON c.id_aluno = a.id_alunos AND c.id_turma = at.id_turma AND c.data_chamada = ?
  WHERE at.id_turma = ?
  ORDER BY at.numero_chamada
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$data, $id_turma]);
$alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($alunos);
