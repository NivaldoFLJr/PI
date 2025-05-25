<?php
include 'servidor.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"] ?? '';
    $cpf = $_POST["cpf"] ?? '';
    $email = $_POST["email"] ?? '';
    $senha = $_POST["senha"] ?? '';

    if (empty($nome) || empty($cpf) || empty($email) || empty($senha)) {
        echo "
               <script>
                  alert('Todos os campos são obrigatorios.');
                  window.location.href = 'cadastro.html';
               </script>
            ";
    }

    $stmt = $OOP->prepare("INSERT INTO usuarios (nome, cpf, email, senha) VALUES (?, ?, ?, ?)");

    if ($stmt) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt->bind_param("ssss", $nome, $cpf, $email, $senha_hash);

        if ($stmt->execute()) {
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
