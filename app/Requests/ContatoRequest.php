<?php

namespace App\Requests;

class ContatoValiador {
    private $dados;
    private $erros;

    public function __construct(array $dados)
    {
        $this->dados = $dados;
        $this->erros = [];
    }

    public function validar(): array {
        $this->validarNome();
        $this->validarEmail();
        $this->validarSexo();
        $this->validarData();
        $this->validarTelefone();
        $this->validarPais();
        $this->validarImagem();

        return $this->erros;
    }

    public function validarNome() {
        $nome = trim($this->dados['nome'] ?? '');

        if (empty($this->dados['nome'])) {
            $this->erros[] = "O nome é obrigatorio.";
        } elseif (strlen($nome) < 3) {
            $this->erros[] = "O nome deve ter pelo menos 3 caracteres.";
        } elseif (strlen($nome) > 100) {
            $this->erros[] = "O nome deve ter no máximo 100 caracteres.";
        }
    }

    public function validarEmail() {
        $email = trim($this->dados['email'] ?? '');

        if (empty($email)) {
            $this->erros[] = "O e-mail é obrigatorio.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->erros[] = "E-mail inválido.";
        }
    }

    public function validarSexo() {
        $sexo = strtolower($this->dados['sexo'] ?? '');
        
        if (empty($sexo)) {
            $this->erros[] = "O sexo é obrigatorio.";
        } 
    }

    private function validarData() {
        $data = $this->dados['nascimento'] ?? '';

        if (empty($data)) {
            $this->erros[] = "A data é obrigatória.";
        }
    }

    private function validarTelefone() {
        $telefone = preg_replace('/[^0-9]/', '', $this->dados['telefone'] ?? '');

        if (empty($telefone)) {
            $this->erros[] = "O telefone é obrigatório.";
        } elseif (strlen($telefone) < 10 || strlen($telefone) > 15) {
            $this->erros[] = "O telefone deve conter entre 10 e 15 dígitos.";
        }
    }

    private function validarPais() {
        $pais = trim($this->dados['id_pais'] ?? '');

        if (empty($pais)) {
            $this->erros[] = "O país é obrigatório.";
        } 
    }

    private function validarImagem() {
        $imagem = $this->dados['imagem'] ?? null;
        $imagemAtual = $this->dados['imagem_atual'] ?? null;

        // Se nenhuma imagem nova foi enviada e já existe uma imagem antiga, está ok
        if (
            (empty($imagem) || (is_array($imagem) && $imagem['error'] === UPLOAD_ERR_NO_FILE)) 
            && !empty($imagemAtual)
        ) {
            return; // Nenhum erro, imagem existente será mantida
        }

        // Se não tem imagem nova nem antiga, erro
        if (empty($imagem)) {
            $this->erros[] = "A foto é obrigatória.";
            return;
        }

        // Se imagem foi enviada mas com erro
        if (is_array($imagem)) {
            if ($imagem['error'] !== UPLOAD_ERR_OK) {
                $this->erros[] = "Erro ao fazer upload da foto.";
            } else {
                $extensao = pathinfo($imagem['name'], PATHINFO_EXTENSION);
                $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];

                if (!in_array(strtolower($extensao), $extensoesPermitidas)) {
                    $this->erros[] = "Formato de imagem inválido. Aceitos: JPG, JPEG, PNG, GIF.";
                }

                if ($imagem['size'] > 2 * 1024 * 1024) { // 2MB
                    $this->erros[] = "A foto deve ter no máximo 2MB";
                }
            }
        }
    }
}

?>