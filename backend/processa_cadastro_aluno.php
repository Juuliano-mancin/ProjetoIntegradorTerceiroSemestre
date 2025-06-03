<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $sql = "INSERT INTO tb_alunos (
            nome, nome_social, sobrenome, genero, nascimento, cpf, rg, matricula,
            tipo_matricula, semestre, telefone, email, cep, logradouro, numero,
            complemento, bairro, cidade, uf, cont_emergencia_nome,
            cont_emergencia_relacao, cont_emergencia_contato, status
        ) VALUES (
            :nome, :nome_social, :sobrenome, :genero, :nascimento, :cpf, :rg, :matricula,
            :tipo_matricula, :semestre, :telefone, :email, :cep, :logradouro, :numero,
            :complemento, :bairro, :cidade, :uf, :cont_emergencia_nome,
            :cont_emergencia_relacao, :cont_emergencia_contato, :status
        )";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':nome' => $_POST['nome'],
            ':nome_social' => $_POST['nome_social'] ?? null,
            ':sobrenome' => $_POST['sobrenome'],
            ':genero' => $_POST['genero'],
            ':nascimento' => $_POST['nascimento'],
            ':cpf' => $_POST['cpf'],
            ':rg' => $_POST['rg'],
            ':matricula' => $_POST['matricula'],
            ':tipo_matricula' => $_POST['tipo_matricula'],
            ':semestre' => $_POST['semestre'],
            ':telefone' => $_POST['telefone'] ?? null,
            ':email' => $_POST['email'] ?? null,
            ':cep' => $_POST['cep'] ?? null,
            ':logradouro' => $_POST['logradouro'],
            ':numero' => $_POST['numero'] ?? null,
            ':complemento' => $_POST['complemento'] ?? null,
            ':bairro' => $_POST['bairro'],
            ':cidade' => $_POST['cidade'],
            ':uf' => $_POST['uf'],
            ':cont_emergencia_nome' => $_POST['cont_emergencia_nome'] ?? null,
            ':cont_emergencia_relacao' => $_POST['cont_emergencia_relacao'] ?? 'nenhum',
            ':cont_emergencia_contato' => $_POST['cont_emergencia_contato'] ?? null,
            ':status' => $_POST['status']
        ]);

        echo "<h2>Aluno cadastrado com sucesso!<h2>";
    } catch (PDOException $e) {
        error_log("Erro ao inserir aluno: " . $e->getMessage());
        echo "Erro ao cadastrar aluno. Verifique os dados.";
    }
} else {
    echo "<h2>Acesso inválido.<h2>";
}