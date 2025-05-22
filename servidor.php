<?php
    define('SERVIDOR', 'localhost');
    define('USUARIO', 'root');
    define('SENHA', '');
    define('BANCO', 'usuarios');
    define('PORTA', 3306);

    $OOP = new mysqli(SERVIDOR, USUARIO, SENHA, BANCO, PORTA);

    if ($OOP->connect_error) {
        die("Falha na conexão: " . $OOP->connect_error);
    }
?>
