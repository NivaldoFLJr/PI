<?php
    include 'servidor.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = $_POST["nome"] ?? '';
        $cpf = $_POST["cpf"] ?? '';
        $email = $_POST["email"] ?? '';
        $senha = $_POST["senha"] ?? '';

        if (empty($nome) || empty($cpf) || empty($email) || empty($senha)) {
            die("Todos os campos devem ser preenchidos.");
        }

        $stmt = $OOP->prepare("INSERT INTO usuarios (nome, cpf, email, senha) VALUES (?, ?, ?, ?)");

        if ($stmt) {
            $stmt->bind_param("ssss", $nome, $cpf, $email, password_hash($senha, PASSWORD_DEFAULT));

            if ($stmt->execute()) {
                echo "Cadastro realizado com sucesso!";
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