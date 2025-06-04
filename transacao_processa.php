<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.html");
    exit();
}

include 'servidor.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_usuario = $_SESSION['id_usuario'];
    $id_categoria = $_POST["id_categoria"] ?? null;
    $tipo_transacao = $_POST["tipo_transacao"] ?? null;
    $id_conta = $_POST["id_conta"] ?? null;
    $data_transacao = $_POST["data_transacao"] ?? null;
    $valor = $_POST["valor"] ?? null;
    $descricao = $_POST["descricao"] ?? '';

    if (!$id_categoria || !$tipo_transacao || !$id_conta || !$data_transacao || !$valor) {
        echo "<script>alert('Todos os campos obrigatórios devem ser preenchidos.'); window.location.href = 'transacao.php';</script>";
        exit();
    }

    $sql = "INSERT INTO transacoes (id_usuario, id_conta, id_categoria, tipo_transacao, data_transacao, valor, descricao)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $OOP->prepare($sql);
    $stmt->bind_param("iiissds", $id_usuario, $id_conta, $id_categoria, $tipo_transacao, $data_transacao, $valor, $descricao);

    if ($stmt->execute()) {
        $operador = ($tipo_transacao === 'receita') ? '+' : '-';
        $sqlUpdate = "UPDATE contas SET saldo = saldo $operador ? WHERE id_conta = ?";
        $stmtUpdate = $OOP->prepare($sqlUpdate);
        $stmtUpdate->bind_param("di", $valor, $id_conta);
        $stmtUpdate->execute();

        echo "<script>alert('Transação registrada com sucesso!'); window.location.href = 'transacao.php';</script>";
    } else {
        echo "Erro ao registrar transação: " . $stmt->error;
    }

    $stmt->close();
    $OOP->close();
}
?>
