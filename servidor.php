<?php
$env = parse_ini_file(__DIR__ . '/.env');

$servidor = $env['DB_HOST'];
$usuario  = $env['DB_USER'];
$senha    = $env['DB_PASS'];
$banco    = $env['DB_NAME'];

$OOP = new mysqli($servidor, $usuario, $senha, $banco);

if ($OOP->connect_error) {
    die("Falha na conexão: " . $OOP->connect_error);
}

$OOP->set_charset("utf8mb4");
?>
