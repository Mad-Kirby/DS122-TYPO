<?php

function carregarEnv($caminho)
{
    if (!file_exists($caminho)) {
        die("Arquivo .env não encontrado.");
    }

    $linhas = file($caminho, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($linhas as $linha) {
        $linha = trim($linha);

        if ($linha === "" || str_starts_with($linha, "#")) {
            continue;
        }

        $partes = explode("=", $linha, 2);

        if (count($partes) !== 2) {
            continue;
        }

        $chave = trim($partes[0]);
        $valor = trim($partes[1]);

        $_ENV[$chave] = $valor;
    }
}

carregarEnv(__DIR__ . "/../.env");

$host = $_ENV["DB_HOST"] ?? "localhost";
$dbname = $_ENV["DB_NAME"] ?? "";
$user = $_ENV["DB_USER"] ?? "";
$password = $_ENV["DB_PASSWORD"] ?? "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Erro ao conectar com o banco de dados.");
}