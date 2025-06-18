<?php
function carregarEnv($arquivo = __DIR__ . '/../.env') {
    if (!file_exists($arquivo)) {
        throw new Exception(".env não encontrado");
    }
    $linhas = file($arquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($linhas as $linha) {
        if (strpos(trim($linha), '#') === 0) continue; // ignora comentários
        list($chave, $valor) = explode('=', $linha, 2);
        // define variável no ambiente PHP
        putenv(trim($chave) . '=' . trim($valor));
        $_ENV[trim($chave)] = trim($valor);
        $_SERVER[trim($chave)] = trim($valor);
    }
}

function conectarBanco(): PDO {
    carregarEnv();
    $host = getenv('DB_HOST');    // espera que tenha no .env
    $banco = getenv('DB_NAME');
    $usuario = getenv('DB_USER');
    $senha = getenv('DB_PASS');
    
    try {
        $conexao = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuario, $senha);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexao;
    } catch (PDOException $e) {
        die("Erro ao conectar com o banco de dados: " . $e->getMessage());
    }
}
