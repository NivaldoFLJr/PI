<?php
include 'servidor.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"] ?? '';
    $cpf = $_POST["cpf"] ?? '';
    $email = $_POST["email"] ?? '';
    $senha = $_POST["senha"] ?? '';

    if (empty($nome) || empty($cpf) || empty($email) || empty($senha)) {
        echo "<script>alert('Todos os campos são obrigatórios.'); window.location.href = 'cadastro.html';</script>";
        exit();
    }

    $verifica = $OOP->prepare("SELECT id_usuario FROM usuarios WHERE cpf = ? OR email = ?");
    $verifica->bind_param("ss", $cpf, $email);
    $verifica->execute();
    $verifica->store_result();
    if ($verifica->num_rows > 0) {
        echo "<script>alert('CPF ou e-mail já cadastrado.'); window.location.href = 'cadastro.html';</script>";
        exit();
    }

    $stmt = $OOP->prepare("INSERT INTO usuarios (nome, cpf, email, senha) VALUES (?, ?, ?, ?)");

    if ($stmt) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt->bind_param("ssss", $nome, $cpf, $email, $senha_hash);

        if ($stmt->execute()) {
            $id_usuario = $stmt->insert_id;

            $stmt_conta = $OOP->prepare("INSERT INTO contas (id_usuario, nome_conta, tipo_conta, saldo) VALUES (?, ?, ?, ?)");
            $nome_conta = $nome;
            $tipo_conta = 'corrente'; 
            $saldo_inicial = 0.00;
            $stmt_conta->bind_param("issd", $id_usuario, $nome_conta, $tipo_conta, $saldo_inicial);
            $stmt_conta->execute();
            $stmt_conta->close();

            echo "
                <script>
                    alert('Cadastro efetuado com sucesso!');
                    window.location.href = 'login.html';
                </script>
            ";
            exit();
        } else {
            echo "Erro ao cadastrar: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Erro na preparação da consulta: " . $OOP->error;
    }

    $OOP->close();
}
?>
