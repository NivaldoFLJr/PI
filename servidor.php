<?php
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "usuarios";

$OOP = new mysqli($servidor, $usuario, $senha, $banco);

if ($OOP->connect_error) {
    die("Falha na conexão: " . $OOP->connect_error);
}
?>
