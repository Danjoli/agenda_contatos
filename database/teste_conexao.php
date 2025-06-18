<?php
require_once 'conexao.php'; // ajuste o caminho!

try {
    $pdo = conectarBanco();
    echo "✅ Conexão bem-sucedida com o banco de dados!";
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage();
}
