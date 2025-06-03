<?php
session_start();
require_once "servidor.php";

$id_categoria = $_POST['id_categoria'];
$tipo = $_POST['tipo_transacao'];
$data = $_POST['data_transacao'];
$valor = $_POST['valor'];
$descricao = $_POST['descricao'];

$sql = "INSERT INTO transacoes (id_categoria, tipo_transacao, data_transacao, valor, descricao)
        VALUES (?, ?, ?, ?, ?)";
$stmt = $OOP->prepare($sql);
$stmt->bind_param("iiissds", $id_categoria, $tipo, $data, $valor, $descricao);

if ($stmt->execute()) {
    echo "Transação adicionada com sucesso! <a href='extrato.html'>Ver extrato</a>";
} else {
    echo "Erro ao adicionar transação: " . $stmt->error;
}
?>
