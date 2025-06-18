<?php
namespace App\Models;

class ContatoModel
{
    private $conexao;

    public function __construct()
    {
        // Inclui o arquivo da conexão
        require_once __DIR__ . '/../../database/conexao.php';

        // Usa a variável $conexao definida no arquivo incluso
        $this->conexao = conectarBanco();
    }

    public function paises()
    {
        $sql = "SELECT * FROM paises";  

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC); // Retorna os resultados em forma de array associativo
    }

    public function salvar(array $dados): bool
    {
        $sql = "INSERT INTO contatos (nome, email, sexo, nascimento, telefone, id_pais, imagem)
                VALUES (:nome, :email, :sexo, :nascimento, :telefone, :id_pais, :imagem)";

        $stmt = $this->conexao->prepare($sql);

        // Exemplo básico, deve ajustar tratamento para foto etc
        return $stmt->execute([
            ':nome' => $dados['nome'],
            ':email' => $dados['email'],
            ':sexo' => $dados['sexo'],
            ':nascimento' => $dados['nascimento'],
            ':telefone' => $dados['telefone'],
            ':id_pais' => $dados['id_pais'],
            ':imagem' => $dados['imagem'] ?? null,
        ]);
    }

    public function contatos()
    {
        $sql = "SELECT * FROM contatos";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function contatosSelecionado($email)
    {
        $sql = "SELECT * FROM contatos WHERE email = :email";

        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function primeiroContato()
    {
        $sql = "SELECT * FROM contatos ORDER BY email ASC LIMIT 1";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function buscarPorEmail($email)
    {
        $sql = "SELECT * FROM contatos WHERE email = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function deletarPorEmail($email)
    {
        $sql = "DELETE FROM contatos WHERE email = ?";
        $stmt = $this->conexao->prepare($sql);
        return $stmt->execute([$email]);
    }

    public function atualizar($dados)
    {
        try {
            $sql = "UPDATE contatos SET 
                        nome = :nome,
                        sexo = :sexo,
                        nascimento = :nascimento,
                        telefone = :telefone,
                        id_pais = :id_pais,
                        imagem = :imagem
                    WHERE email = :email";

            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':nome', $dados['nome']);
            $stmt->bindValue(':sexo', $dados['sexo']);
            $stmt->bindValue(':nascimento', $dados['nascimento']);
            $stmt->bindValue(':telefone', $dados['telefone']);
            $stmt->bindValue(':id_pais', $dados['id_pais']);
            $stmt->bindValue(':imagem', $dados['imagem']);
            $stmt->bindValue(':email', $dados['email']);

            return $stmt->execute();
        } catch (\PDOException $e) {
            // Opcional: log de erro ou exibição para debug
            // echo "Erro ao atualizar: " . $e->getMessage();
            return false;
        }
    }

    public function buscarTodos()
    {
        $sql = "SELECT * FROM contatos";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function buscarPorNome($nome)
    {
        $sql = "SELECT * FROM contatos WHERE nome = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$nome]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function buscarPorSexo($sexo)
    {
        $sql = "SELECT * FROM contatos WHERE sexo = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$sexo]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function buscarPorPais($idPais)
    {
        $sql = "SELECT * FROM contatos WHERE id_pais = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$idPais]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function buscarPorInicial(string $inicial): array
    {
        $inicial = trim($inicial);
        if ($inicial === '') {
            return [];
        }

        $sql = "SELECT * FROM contatos 
                WHERE nome LIKE ? OR email LIKE ?
                ORDER BY nome ASC";

        $stmt = $this->conexao->prepare($sql);
        $likeInicial = $inicial . '%';
        $stmt->execute([$likeInicial, $likeInicial]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function buscaGeral($valor)
    {
        $valor = trim($valor);
        if ($valor === '') {
            return [];
        }

        $sql = "SELECT * FROM contatos 
                WHERE nome LIKE ? 
                OR email LIKE ? 
                OR telefone LIKE ?
                ORDER BY nome";

        $stmt = $this->conexao->prepare($sql);
        $likeValor = '%' . $valor . '%';
        $stmt->execute([$likeValor, $likeValor, $likeValor]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
?>