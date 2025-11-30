<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.html");
    exit();
}

include 'servidor.php';

$id_usuario = $_SESSION['id_usuario'];

$data_inicio = $_GET['data_inicio'] ?? null;
$data_fim = $_GET['data_fim'] ?? null;
$tipo_transacao = $_GET['tipo_transacao'] ?? null;

$sql = 
    SELECT 
        t.data_transacao,
        c.nome_conta,
        cat.nome_categoria,
        t.tipo_transacao,
        t.descricao,
        t.valor
    FROM transacoes t
    JOIN contas c ON t.id_conta = c.id_conta
    JOIN categorias cat ON t.id_categoria = cat.id_categoria
    WHERE t.id_usuario = ?
";

$tipos = [];
if ($data_inicio) {
    $sql .= " AND t.data_transacao >= ?";
    $tipos[] = $data_inicio;
}
if ($data_fim) {
    $sql .= " AND t.data_transacao <= ?";
    $tipos[] = $data_fim;
}
if ($tipo_transacao) {
    $sql .= " AND t.tipo_transacao = ?";
    $tipos[] = $tipo_transacao;
}

$sql .= " ORDER BY t.data_transacao DESC";

$stmt = $OOP->prepare($sql);

if (!$stmt) {
    die("Erro ao preparar consulta: " . $OOP->error);
}

$bind_types = str_repeat("s", count($tipos));
$params = array_merge([$id_usuario], $tipos);
$bind_types = "i" . $bind_types;

$stmt->bind_param($bind_types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Extrato</title>
    <link rel="stylesheet" href="styles/extrato.css">
</head>
<body>
    <div class="container">
        <h1>Extrato Geral</h1>

        <section>
            <form method="GET" action="extrato.php">
                <label>Período:</label>
                De <input type="date" name="data_inicio" value="<?= htmlspecialchars($data_inicio) ?>">
                até <input type="date" name="data_fim" value="<?= htmlspecialchars($data_fim) ?>">

                <label>Tipo:</label>
                <select name="tipo_transacao">
                    <option value="">Todos</option>
                    <option value="receita" <?= $tipo_transacao === "receita" ? "selected" : "" ?>>Receita</option>
                    <option value="despesa" <?= $tipo_transacao === "despesa" ? "selected" : "" ?>>Despesa</option>
                </select>

                <input type="submit" value="Filtrar">
            </form>
        </section>

        <section>
            <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Conta</th>
                        <th>Categoria</th>
                        <th>Tipo</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($linha = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($linha['data_transacao']) ?></td>
                            <td><?= htmlspecialchars($linha['nome_conta']) ?></td>
                            <td><?= htmlspecialchars($linha['nome_categoria']) ?></td>
                            <td><?= htmlspecialchars($linha['tipo_transacao']) ?></td>
                            <td><?= htmlspecialchars($linha['descricao']) ?></td>
                            <td>R$ <?= number_format($linha['valor'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if ($result->num_rows === 0): ?>
                        <tr><td colspan="6">Nenhuma transação encontrada.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <section class="links">
            <a href="transacao.php">➕ Nova Transação</a>
            <a href="index.php">Voltar à página inicial</a>
            <a href="logout.php">🚪 Sair</a>
        </section>
    </div>
</body>
</html>
