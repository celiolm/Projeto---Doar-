<?php
$host     = '127.0.0.1';
$porta    = '3306';
$dbname   = 'doacaomais';
$usuario  = 'teste';
$senha    = 'teste';
$caminhoCA = __DIR__ . '/ca.pem';

try {
    $dsn = "mysql:host=$host;port=$porta;dbname=$dbname;charset=utf8mb4";
    $opcoes = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_SSL_CA => $caminhoCA,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true,
    ];
    $pdo = new PDO($dsn, $usuario, $senha, $opcoes);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}
