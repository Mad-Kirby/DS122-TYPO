<?php

// Função responsável por carregar as variáveis do arquivo .env.
function carregarEnv($caminho)
{
    if (!file_exists($caminho)) {
        die("Arquivo .env não encontrado.");
    }

    $linhas = file($caminho, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($linhas as $linha) {
        $linha = trim($linha);

        // Ignora linhas vazias e comentários dentro do arquivo .env.
        if ($linha === "" || str_starts_with($linha, "#")) {
            continue;
        }

        // Divide cada linha do .env no formato CHAVE=VALOR.
        $partes = explode("=", $linha, 2);

        if (count($partes) !== 2) {
            continue;
        }

        $chave = trim($partes[0]);
        $valor = trim($partes[1]);

        $_ENV[$chave] = $valor;
    }
}

// Carrega o arquivo .env localizado na raiz do projeto.
carregarEnv(__DIR__ . "/../.env");

// Define os dados de conexão com o banco a partir das variáveis carregadas.
$host = $_ENV["DB_HOST"] ?? "localhost";
$dbname = $_ENV["DB_NAME"] ?? "";
$user = $_ENV["DB_USER"] ?? "";
$password = $_ENV["DB_PASSWORD"] ?? "";

try {
    // Cria a conexão com o banco usando PDO.
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $password
    );

    // Configura o PDO para lançar exceções em caso de erro.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Erro ao conectar com o banco de dados.");
}