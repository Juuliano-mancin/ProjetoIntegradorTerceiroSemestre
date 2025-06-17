<?php
include 'conexao.php';
// Função para validar CPF (coloque AQUI, antes das outras funções)
function validarCPF($cpf) {
    // Remove caracteres não numéricos
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    
    // Verifica se tem 11 dígitos
    if (strlen($cpf) != 11) {
        return false;
    }
    
    // Verifica sequência repetida
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }
    
    // Validação dos dígitos verificadores
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    
    return true;
}
function carregarPaginaComCSS($titulo, $mensagem, $tipo = 'success') {
    $cor = $tipo;
    $icone = ($tipo === 'success') ? 'check-circle-fill' : 'exclamation-triangle-fill';
    
    echo <<<HTML
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Alunos - $titulo</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilo.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body class="text-white bg-dark">
    <header class="p-3 bg-primary">
        <h1 class="fw-bold ms-3">Cadastro de Alunos</h1>
    </header>

    <div class="container mt-5">
        <div class="alert alert-$cor d-flex align-items-center" role="alert">
            <i class="bi bi-$icone me-2 fs-4"></i>
            <div>
                <h4 class="alert-heading">$titulo</h4>
                <p>$mensagem</p>
                <hr>
                <a href="../frontend/cadastro_alunos.html" class="btn btn-outline-$cor">
                    <i class="bi bi-arrow-left me-2"></i>Voltar para o cadastro
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
HTML;
}

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
            ':status' => 'ativo'
        ];

        // ========== VALIDAÇÕES ========== //

        // 1. Validação da idade (mínimo 13 anos)
        $dataNascimento = new DateTime($dados[':nascimento']);
        $hoje = new DateTime();
        $idade = $hoje->diff($dataNascimento)->y;

        if ($idade < 13) {
            throw new Exception("Aluno deve ter pelo menos 13 anos de idade.");
        }

        // 2. Validação do responsável para menores de 18 anos
        if ($idade < 18) {
            if (empty($dados[':cont_emergencia_nome']) || empty($dados[':cont_emergencia_contato'])) {
                throw new Exception("Dados do responsável (nome e contato) são obrigatórios para menores de 18 anos.");
            }
        }

        // 3. Validação completa do CPF
        if (!validarCPF($dados[':cpf'])) {
            throw new Exception("CPF inválido. Verifique se digitou corretamente.");
        }

        // 4. Validação de RG (formato e dígitos repetidos)
        if (preg_match('/^(\d)\1{8}$/', $dados[':rg'])) {
            throw new Exception("RG inválido (não pode ser uma sequência repetida).");
        }

        // 5. Validação de e-mail (se fornecido)
        if (!empty($dados[':email']) && !filter_var($dados[':email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("E-mail inválido.");
        }

        // 6. Verificar se CPF já existe
        $stmt = $pdo->prepare("SELECT id_alunos FROM tb_alunos WHERE cpf = :cpf");
        $stmt->execute([':cpf' => $dados[':cpf']]);
        if ($stmt->fetch()) {
            throw new Exception("CPF já cadastrado no sistema.");
        }

        // 7. Verificar se RG já existe
        $stmt = $pdo->prepare("SELECT id_alunos FROM tb_alunos WHERE rg = :rg");
        $stmt->execute([':rg' => $dados[':rg']]);
        if ($stmt->fetch()) {
            throw new Exception("RG já cadastrado no sistema.");
        }

        // ========== INSERÇÃO NO BANCO ========== //
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
        carregarPaginaComCSS(
            "Sucesso!",
            "Aluno cadastrado com sucesso! Matrícula gerada automaticamente pelo sistema.",
            'success'
        );

    } catch (PDOException $e) {
        error_log("Erro ao inserir aluno: " . $e->getMessage());
        carregarPaginaComCSS(
            "Erro no Banco de Dados",
            $e->getMessage(),
            'danger'
        );
    } catch (Exception $e) {
        error_log("Erro de validação: " . $e->getMessage());
        carregarPaginaComCSS(
            "Erro de Validação",
            $e->getMessage(),
            'danger'
        );
    }
} else {
    carregarPaginaComCSS(
        "Acesso Inválido",
        "Esta página só pode ser acessada via formulário de cadastro.",
        'warning'
    );
}