<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Limpar e validar dados
        $dados = [
            ':nome' => filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING),
            ':nome_social' => filter_input(INPUT_POST, 'nome_social', FILTER_SANITIZE_STRING) ?: null,
            ':sobrenome' => filter_input(INPUT_POST, 'sobrenome', FILTER_SANITIZE_STRING),
            ':genero' => filter_input(INPUT_POST, 'genero', FILTER_SANITIZE_STRING),
            ':nascimento' => filter_input(INPUT_POST, 'nascimento', FILTER_SANITIZE_STRING),
            ':cpf' => preg_replace('/[^0-9]/', '', $_POST['cpf']),
            ':rg' => preg_replace('/[^0-9]/', '', $_POST['rg']),
            ':tipo_matricula' => filter_input(INPUT_POST, 'tipo_matricula', FILTER_SANITIZE_STRING),
            ':semestre' => filter_input(INPUT_POST, 'semestre', FILTER_VALIDATE_INT),
            ':telefone' => preg_replace('/[^0-9]/', '', $_POST['telefone']) ?: null,
            ':email' => filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) ?: null,
            ':cep' => preg_replace('/[^0-9]/', '', $_POST['cep']) ?: null,
            ':logradouro' => filter_input(INPUT_POST, 'logradouro', FILTER_SANITIZE_STRING),
            ':numero' => filter_input(INPUT_POST, 'numero', FILTER_SANITIZE_STRING) ?: null,
            ':complemento' => filter_input(INPUT_POST, 'complemento', FILTER_SANITIZE_STRING) ?: null,
            ':bairro' => filter_input(INPUT_POST, 'bairro', FILTER_SANITIZE_STRING),
            ':cidade' => filter_input(INPUT_POST, 'cidade', FILTER_SANITIZE_STRING),
            ':uf' => filter_input(INPUT_POST, 'uf', FILTER_SANITIZE_STRING),
            ':cont_emergencia_nome' => filter_input(INPUT_POST, 'cont_emergencia_nome', FILTER_SANITIZE_STRING) ?: null,
            ':cont_emergencia_relacao' => filter_input(INPUT_POST, 'cont_emergencia_relacao', FILTER_SANITIZE_STRING) ?: 'nenhum',
            ':cont_emergencia_contato' => preg_replace('/[^0-9]/', '', $_POST['cont_emergencia_contato']) ?: null,
            ':status' => 'ativo' // Definido como padrão
        ];

        // Verificar se CPF já existe
        $stmt = $pdo->prepare("SELECT id_alunos FROM tb_alunos WHERE cpf = :cpf");
        $stmt->execute([':cpf' => $dados[':cpf']]);
        if ($stmt->fetch()) {
            throw new Exception("CPF já cadastrado no sistema.");
        }

        // Verificar se RG já existe
        $stmt = $pdo->prepare("SELECT id_alunos FROM tb_alunos WHERE rg = :rg");
        $stmt->execute([':rg' => $dados[':rg']]);
        if ($stmt->fetch()) {
            throw new Exception("RG já cadastrado no sistema.");
        }

        // Inserir aluno (a matrícula será gerada automaticamente pelo trigger)
        $sql = "INSERT INTO tb_alunos (
            nome, nome_social, sobrenome, genero, nascimento, cpf, rg,
            tipo_matricula, semestre, telefone, email, cep, logradouro, numero,
            complemento, bairro, cidade, uf, cont_emergencia_nome,
            cont_emergencia_relacao, cont_emergencia_contato, status
        ) VALUES (
            :nome, :nome_social, :sobrenome, :genero, :nascimento, :cpf, :rg,
            :tipo_matricula, :semestre, :telefone, :email, :cep, :logradouro, :numero,
            :complemento, :bairro, :cidade, :uf, :cont_emergencia_nome,
            :cont_emergencia_relacao, :cont_emergencia_contato, :status
        )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($dados);

        // Mensagem de sucesso
        echo '<div class="container mt-4">';
        echo '<div class="alert alert-success" role="alert">';
        echo '<h2>Aluno cadastrado com sucesso!</h2>';
        echo '<p>Matrícula gerada automaticamente pelo sistema.</p>';
        echo '</div>';
        echo '<a href="../frontend/cadastro_alunos.html" class="btn btn-primary">Voltar</a>';
        echo '</div>';

    } catch (PDOException $e) {
        error_log("Erro ao inserir aluno: " . $e->getMessage());
        echo '<div class="container mt-4">';
        echo '<div class="alert alert-danger" role="alert">';
        echo '<h2>Erro ao cadastrar aluno</h2>';
        echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '</div>';
        echo '<a href="../frontend/cadastro_alunos.html" class="btn btn-primary">Voltar</a>';
        echo '</div>';
    } catch (Exception $e) {
        error_log("Erro de validação: " . $e->getMessage());
        echo '<div class="container mt-4">';
        echo '<div class="alert alert-danger" role="alert">';
        echo '<h2>Erro de validação</h2>';
        echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '</div>';
        echo '<a href="../frontend/cadastro_alunos.html" class="btn btn-primary">Voltar</a>';
        echo '</div>';
    }
} else {
    echo '<div class="container mt-4">';
    echo '<div class="alert alert-warning" role="alert">';
    echo '<h2>Acesso inválido.</h2>';
    echo '</div>';
    echo '<a href="../frontend/cadastro_alunos.html" class="btn btn-primary">Voltar</a>';
    echo '</div>';
}