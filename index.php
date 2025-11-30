<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.html");
    exit();
}

include 'servidor.php';

$id_usuario = $_SESSION['id_usuario'];
$nome = $_SESSION['nome'];

$sqlSaldo = "SELECT SUM(saldo) AS saldo_total FROM contas WHERE id_usuario = ?";
$stmtSaldo = $OOP->prepare($sqlSaldo);
$stmtSaldo->bind_param("i", $id_usuario);
$stmtSaldo->execute();
$resultadoSaldo = $stmtSaldo->get_result();
$saldoTotal = $resultadoSaldo->fetch_assoc()['saldo_total'] ?? 0.00;
$stmtSaldo->close();

$sqlTransacoes = "
    SELECT 
        t.data_transacao, 
        t.tipo_transacao, 
        t.valor, 
        t.descricao, 
        c.nome_conta,
        cat.nome_categoria
    FROM transacoes t
    JOIN contas c ON t.id_conta = c.id_conta
    JOIN categorias cat ON t.id_categoria = cat.id_categoria
    WHERE t.id_usuario = ?
    ORDER BY t.data_transacao DESC
    LIMIT 5
";
$stmtTransacoes = $OOP->prepare($sqlTransacoes);
$stmtTransacoes->bind_param("i", $id_usuario);
$stmtTransacoes->execute();
$resultadoTransacoes = $stmtTransacoes->get_result();

include 'header.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - MyFinance</title>
    <link rel="stylesheet" href="styles/dashboard.css">
</head>
<body>
    <div class="container">
        <h1>Bem-vindo, <?= htmlspecialchars($nome) ?>!</h1>

        <section>
            <h2>Saldo total: R$ <?= number_format($saldoTotal, 2, ',', '.') ?></h2>
        </section>

        <section>
            <h2>Últimas transações</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Tipo</th>
                        <th>Valor</th>
                        <th>Conta</th>
                        <th>Categoria</th>
                        <th>Descrição</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultadoTransacoes->num_rows > 0): ?>
                        <?php while ($row = $resultadoTransacoes->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['data_transacao']) ?></td>
                                <td><?= htmlspecialchars($row['tipo_transacao']) ?></td>
                                <td>R$ <?= number_format($row['valor'], 2, ',', '.') ?></td>
                                <td><?= htmlspecialchars($row['nome_conta']) ?></td>
                                <td><?= htmlspecialchars($row['nome_categoria']) ?></td>
                                <td><?= htmlspecialchars($row['descricao']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6">Nenhuma transação registrada.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>
