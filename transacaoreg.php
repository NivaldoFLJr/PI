<?php
include 'servidor.php';

$usuarios = $OOP->query("SELECT id_usuario, nome FROM usuarios");
$contas = $OOP->query("SELECT id_conta, nome_conta FROM contas");
$categorias = $OOP->query("SELECT id_categoria, nome_categoria FROM categorias");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Registrar Transação</title>
</head>
<body>
    <h1>Registrar Nova Transação</h1>

    <form method="POST" action="salvar_transacao.php">
        <label>Usuário:</label>
        <select name="id_usuario" required>
            <option value="">Selecione</option>
            <?php while($u = $usuarios->fetch_assoc()): ?>
                <option value="<?= $u['id_usuario'] ?>"><?= htmlspecialchars($u['nome']) ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label>Conta:</label>
        <select name="id_conta" required>
            <option value="">Selecione</option>
            <?php while($c = $contas->fetch_assoc()): ?>
                <option value="<?= $c['id_conta'] ?>"><?= htmlspecialchars($c['nome_conta']) ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label>Categoria:</label>
        <select name="id_categoria" required>
            <option value="">Selecione</option>
            <?php while($cat = $categorias->fetch_assoc()): ?>
                <option value="<?= $cat['id_categoria'] ?>"><?= htmlspecialchars($cat['nome_categoria']) ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label>Tipo:</label>
        <select name="tipo_transacao" required>
            <option value="">Selecione</option>
            <option value="despesa">Despesa</option>
            <option value="receita">Receita</option>
        </select><br><br>

        <label>Data:</label>
        <input type="date" name="data_transacao" required><br><br>

        <label>Descrição:</label>
        <input type="text" name="descricao" maxlength="255"><br><br>

        <label>Valor:</label>
        <input type="number" step="0.01" name="valor" required><br><br>

        <input type="submit" value="Salvar Transação">
    </form>
</body>
</html>

<?php
$OOP->close();
?>
