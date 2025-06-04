<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.html");
    exit();
}

include 'servidor.php';

$id_usuario = $_SESSION['id_usuario'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Transação - MyFinance</title>
</head>
<body>
    <h1>Adicionar Movimentação</h1>
    <section>
        <form method="POST" action="transacao_processa.php">
            Categoria:
            <select name="id_categoria" required>
                <?php
                $sql = "SELECT id_categoria, nome_categoria FROM categorias";
                $resultado = $OOP->query($sql);
                while ($row = $resultado->fetch_assoc()) {
                    echo "<option value='{$row['id_categoria']}'>{$row['nome_categoria']}</option>";
                }
                ?>
            </select><br>

            Tipo:
            <select name="tipo_transacao" required>
                <option value="despesa">Despesa</option>
                <option value="receita">Receita</option>
            </select><br>

            Conta:
            <select name="id_conta" required>
            <?php
                $sql = "SELECT id_conta, nome_conta FROM contas WHERE id_usuario = ?";
                $stmt = $OOP->prepare($sql);
                $stmt->bind_param("i", $id_usuario);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res && $res->num_rows > 0) {
                    while ($conta = $res->fetch_assoc()) {
                        echo "<option value='{$conta['id_conta']}'>{$conta['nome_conta']}</option>";
                    }
                } else {
                    echo "<option disabled>Nenhuma conta encontrada</option>";
                }
            ?>
            </select>
            Data: <input type="date" name="data_transacao" required><br>
            Valor: <input type="number" name="valor" step="0.01" required><br>
            Descrição: <input type="text" name="descricao"><br>

            <input type="submit" value="Adicionar">
        </form>
    </section>
    <section>
        <p><a href="extrato.php">📄 Ver Extrato</a></p>
        <p><a href="index.php">Voltar ao index</a></p>
        <p><a href="logout.php">🚪 Sair</a></p>
    </section>
</body>
</html>
