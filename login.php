<?php
session_start();
include 'servidor.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cpf = $_POST['cpf'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (empty($cpf) || empty($senha)) {
        echo "<script>alert('Por favor, preencha todos os campos.'); window.location.href = 'login.html';</script>";
        exit();
    }

    $sql = "SELECT id_usuario, nome, senha FROM usuarios WHERE cpf = ?";
    $stmt = $OOP->prepare($sql);
    if (!$stmt) {
        die("Erro de preparação: " . $OOP->error);
    }

    $stmt->bind_param("s", $cpf);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nome'] = $usuario['nome'];

            header("Location: index.php");
            exit();
        } else {
            echo "<script>alert('Senha incorreta.'); window.location.href = 'login.html';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Usuário não encontrado.'); window.location.href = 'login.html';</script>";
        exit();
    }

    $stmt->close();
    $OOP->close();
}
?>
