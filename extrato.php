<?php
session_start();
require_once "servidor.php";

$id_usuario = $_SESSION['id_usuario'];

$data_inicio = $_GET['data_inicio'] ?? null;
$data_fim = $_GET['data_fim'] ?? null;
$tipo = $_GET['tipo_transacao'] ?? null;

$query = "SELECT t.*, c.nome_conta, cat.nome_categoria 
          FROM transacoes t
          JOIN contas c ON t.id_conta = c.id_conta
          JOIN categorias cat ON t.id_categoria = cat.id_categoria
          WHERE t.id_usuario = ?";
$params = [$id_usuario];
$types = "i";

if ($tipo) {
    $query .= " AND t.tipo_transacao = ?";
    $params[] = $tipo;
    $types .= "s";
}

if ($data_inicio && $data_fim) {
    $query .= " AND t.data_transacao BETWEEN ? AND ?";
    $params[] = $data_inicio;
    $params[] = $data_fim;
    $types .= "ss";
}

$query .= " ORDER BY t.data_transacao DESC";

$stmt = $OOP->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

echo "<table border='1'>
<tr>
    <th>Data</th>
    <th>Conta</th>
    <th>Categoria</th>
    <th>Tipo</th>
    <th>Descrição</th>
    <th>Valor</th>
</tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['data_transacao']}</td>
        <td>{$row['nome_conta']}</td>
        <td>{$row['nome_categoria']}</td>
        <td>{$row['tipo_transacao']}</td>
        <td>{$row['descricao']}</td>
        <td>R$ " . number_format($row['valor'], 2, ',', '.') . "</td>
    </tr>";
}
echo "</table>";
?>
