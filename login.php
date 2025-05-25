<?php
include 'servidor.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cpf = $_POST['cpf'] ?? '';
    $senha = $_POST['senha'] ?? '';

   if (empty($cpf) || empty($senha)) {
      echo "
               <script>
                  alert('Todos os campos são obrigatorios.');
                  window.location.href = 'login.html';
               </script>
            ";
      }

    $stmt = $OOP->prepare("SELECT senha FROM usuarios WHERE cpf = ?");
    $stmt->bind_param("s", $cpf);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($senha_hash);
        $stmt->fetch();

        if (password_verify($senha, $senha_hash)) {
            header("Location: index.html");
            exit();
        } else {
            echo "
                <script>
                    alert('Senha incorreta.');
                    window.location.href = 'login.html';
                </script>
            ";
        }
    } else {
        echo "
            <script>
                alert('Usuário não encontrado.');
                window.location.href = 'login.html';
            </script>
        ";
    }

    $stmt->close();
    $OOP->close();
} else {
    echo "
        <script>
            alert('Requisição inválida.');
            window.location.href = 'login.html';
        </script>
    ";
}
?>
