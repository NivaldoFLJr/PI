<?php
session_start();
require_once "servidor.php";

// Obter resumo financeiro
$sql = "SELECT 
            SUM(CASE WHEN tipo_transacao = 'receita' THEN valor ELSE 0 END) AS total_receitas,
            SUM(CASE WHEN tipo_transacao = 'despesa' THEN valor ELSE 0 END) AS total_despesas
        FROM transacoes
        WHERE id_usuario = ?";
$stmt = $OOP->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resumo = $stmt->get_result()->fetch_assoc();
$saldo = $resumo['total_receitas'] - $resumo['total_despesas'];

$sql2 = "SELECT cat.nome_categoria, SUM(t.valor) AS total
         FROM transacoes t
         JOIN categorias cat ON t.id_categoria = cat.id_categoria
         WHERE t.id_usuario = ? AND t.tipo_transacao = 'despesa'
         GROUP BY cat.nome_categoria";
$stmt2 = $OOP->prepare($sql2);
$stmt2->bind_param("i", $id_usuario);
$stmt2->execute();
$result = $stmt2->get_result();

$categorias = [];
$valores = [];
while ($row = $result->fetch_assoc()) {
    $categorias[] = $row['nome_categoria'];
    $valores[] = $row['total'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - MyFinance</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Bem-vindo ao MyFinance</h1>
    <h2>Resumo financeiro</h2>
    <p>Receitas: R$ <?= number_format($resumo['total_receitas'], 2, ',', '.') ?></p>
    <p>Despesas: R$ <?= number_format($resumo['total_despesas'], 2, ',', '.') ?></p>
    <p><strong>Saldo: R$ <?= number_format($saldo, 2, ',', '.') ?></strong></p>

    <h2>Distribuição de despesas</h2>
    <canvas id="graficoDespesas" width="400" height="400"></canvas>

    <script>
    const ctx = document.getElementById('graficoDespesas').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: <?= json_encode($categorias) ?>,
            datasets: [{
                label: 'Despesas por categoria',
                data: <?= json_encode($valores) ?>,
                backgroundColor: [
                    '#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0', '#9966ff', '#ff9f40'
                ]
            }]
        }
    });
    </script>

    <p><a href="transacao.html">Adicionar transação</a></p>
    <p><a href="extrato.html">Ver extrato</a></p>
    <p><a href="logout.php">Sair</a></p>
</body>
</html>
