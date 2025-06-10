<?php
require 'conexao.php';

$filtroData = $_GET['data'] ?? null;

$sql = "SELECT c.id_turma, t.nome_turma, c.data_chamada 
        FROM tb_chamada c
        JOIN tb_turmas t ON c.id_turma = t.id_turmas";

if ($filtroData) {
    $sql .= " WHERE c.data_chamada = :data";
}

$sql .= " GROUP BY c.id_turma, c.data_chamada ORDER BY c.data_chamada DESC";

$stmt = $pdo->prepare($sql);

if ($filtroData) {
    $stmt->bindParam(':data', $filtroData);
}

$stmt->execute();
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($resultados);
?>