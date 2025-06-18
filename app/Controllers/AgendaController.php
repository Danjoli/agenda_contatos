<?php
namespace App\Controllers;

require_once __DIR__ . '/../Models/ContatoModel.php';

use App\Requests\ContatoValiador;
use App\Models\ContatoModel;
;
class AgendaController
{
    protected $model;

    public function __construct()
    {
        $this->model = new ContatoModel();
    }

    // Exibe a página inicial
    public function home()
    {
        $contatos = $this->model->contatos();
        $paises = $this->model->paises();

        $contatoSelecionado = null;
        $idPaisSelecionado = null;
        $nomePaisSelecionado = 'Selecione um país';

        if (!empty($_GET['contato'])) {
            $contatoSelecionado = $this->model->contatosSelecionado($_GET['contato']);

            $idPaisSelecionado = $contatoSelecionado['id_pais'] ?? null;

            if ($idPaisSelecionado) {
                foreach ($paises as $pais) {
                    if ($pais['id_pais'] == $idPaisSelecionado) {
                        $nomePaisSelecionado = $pais['nome'];
                        break;
                    }
                }
            }
        } 
        
        // Opcional, que fica selecionado algum contato
        else {
            $contatoSelecionado = $this->model->primeiroContato();

            // Se pegou o primeiro contato, faz a mesma lógica para o país
            $idPaisSelecionado = $contatoSelecionado['id_pais'] ?? null;

            if ($idPaisSelecionado) {
                foreach ($paises as $pais) {
                    if ($pais['id_pais'] == $idPaisSelecionado) {
                        $nomePaisSelecionado = $pais['nome'];
                        break;
                    }
                }
            }
        }
        // ===============================================

        include '../resources/views/pages/home.php';
    }


    // Exibe o formulário de adicionar contato
    public function adicionar()
    {
        $erros = [];
        $paises = $this->model->paises(); // // Pega os países para o <select>

        // Exibe o formulário com os erros (se existirem)
        include '../resources/views/pages/adicionar.php';
    }

    // Processa o envio do formulário de adicionar contato
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?pagina=adicionar');
            exit;
        }

        // Passa $_POST + arquivo no campo 'imagem' para validação
        $dados = $_POST;
        $dados['imagem'] = $_FILES['imagem'] ?? null;

        $validador = new ContatoValiador($dados);
        $erros = $validador->validar();

        $paises = $this->model->paises();

        if (!empty($erros)) {
            // Apenas define erros, e a view será carregada no final
        } else {
            // Sem erros: mover arquivo para /images/ e setar $dados['foto']
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $arquivoTmp = $_FILES['imagem']['tmp_name'];
                $nomeOriginal = basename($_FILES['imagem']['name']);
                $extensao = pathinfo($nomeOriginal, PATHINFO_EXTENSION);
                $novoNome = uniqid() . '.' . $extensao;
                $pastaDestino = dirname(__DIR__, 2) . '/public/images/';
                if (!is_dir($pastaDestino)) {
                    mkdir($pastaDestino, 0755, true);
                }

                if (move_uploaded_file($arquivoTmp, $pastaDestino . $novoNome)) {
                    $dados['imagem'] = $novoNome;
                } else {
                    $error = "Falha ao salvar a imagem.";
                    include '../resources/views/pages/adicionar.php';
                    exit;
                }
            } else {
                $dados['imagem'] = null;
            }

            // ✅ Aqui estava faltando:
            $salvo = $this->model->salvar($dados);

            if ($salvo) {
                $sucesso = "O contato foi registrado {$dados['email']}";
            } else {
                $error = "Contato não registrado";
            }
        }

        include '../resources/views/pages/adicionar.php';
    }

    public function editar()
    {
        $contatos = $this->model->contatos();
        include '../resources/views/pages/editar.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ?pagina=home');
        exit;
        }

        $dados = $_POST;
        $dados['imagem'] = $_FILES['imagem'] ?? null;
        $dados['imagem_atual'] = $_POST['imagem_atual'] ?? null;

        $validador = new ContatoValiador($dados);
        $erros = $validador->validar();

        $paises = $this->model->paises();
        $contatos = $this->model->contatos();

        $contatoSelecionado = $this->model->buscarPorEmail($dados['email'] ?? '');

        if (!$contatoSelecionado) {
            $error = "Contato não encontrado.";

            $contatos = $this->model->contatos();
            $paises = $this->model->paises();
            $nomePaisSelecionado = 'Selecione um país';

            include '../resources/views/pages/home.php';
            return;
        }

        if (!empty($erros)) {
            $error = "Dados inválidos: " . implode(', ', $erros);

            $contatos = $this->model->contatos();
            $paises = $this->model->paises();
            $idPaisSelecionado = $contatoSelecionado['id_pais'] ?? null;
            $nomePaisSelecionado = 'Selecione um país';

            foreach ($paises as $pais) {
                if ($pais['id_pais'] == $idPaisSelecionado) {
                    $nomePaisSelecionado = $pais['nome'];
                    break;
                }
            }

            include '../resources/views/pages/home.php';
            return;
        }

        // Upload de nova imagem, se enviada
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $arquivoTmp = $_FILES['imagem']['tmp_name'];
            $nomeOriginal = basename($_FILES['imagem']['name']);
            $extensao = pathinfo($nomeOriginal, PATHINFO_EXTENSION);
            $novoNome = uniqid() . '.' . $extensao;
            $pastaDestino = dirname(__DIR__, 2) . '/public/images/';
            if (!is_dir($pastaDestino)) {
                mkdir($pastaDestino, 0755, true);
            }

            if (move_uploaded_file($arquivoTmp, $pastaDestino . $novoNome)) {
                $dados['imagem'] = $novoNome;

                // Remove imagem antiga se existir
                if (!empty($contatoSelecionado['imagem'])) {
                    $caminhoAntigo = $pastaDestino . $contatoSelecionado['imagem'];
                    if (file_exists($caminhoAntigo)) {
                        unlink($caminhoAntigo);
                    }
                }
            } else {
                $error = "Erro ao fazer upload da imagem.";
                include '../resources/views/pages/home.php';
                return;
            }
        } else {
            // Se nenhuma imagem nova for enviada, mantém a antiga
            $dados['imagem'] = $dados['imagem_atual'];
        }

        $atualizado = $this->model->atualizar($dados);

        if ($atualizado) {
            $sucesso = "Contato atualizado com sucesso.";
        } else {
            $error = "Erro ao atualizar o contato.";
        }

        // Recarrega a página home com os dados atualizados
        $contatos = $this->model->contatos();
        $contatoSelecionado = $this->model->buscarPorEmail($dados['email']);
        $idPaisSelecionado = $contatoSelecionado['id_pais'] ?? null;
        $nomePaisSelecionado = 'Selecione um país';
        foreach ($paises as $pais) {
            if ($pais['id_pais'] == $idPaisSelecionado) {
                $nomePaisSelecionado = $pais['nome'];
                break;
            }
        }

        include '../resources/views/pages/home.php';
    }

    public function consulta()
    {
        $consulta = $_GET['consulta'] ?? '';
        $valor = $_GET['valor'] ?? '';
        $contatos = [];

        switch ($consulta) {
            case 'todos':
                $contatos = $this->model->buscarTodos();
                break;

            case 'email':
                $contato = $this->model->buscarPorEmail($valor);
                $contatos = $contato ? [$contato] : [];
                break;

            case 'inicial':
                $contatos = $this->model->buscarPorInicial($valor);
                break;

            case 'nome':
                $contato = $this->model->buscarPorNome($valor);
                $contatos = $contato ? [$contato] : [];
                break;

            case 'sexo':
                $contato = $this->model->buscarPorSexo($valor);
                $contatos = $contato ? [$contato] : [];
                break;

            case 'pais':
                $contato = $this->model->buscarPorPais($valor);
                $contatos = $contato ? [$contato] : [];
                break;

            case 'geral':
                $contatos = $this->model->buscaGeral($valor);
                break;
        }

        $paises = $this->model->paises();
        $paisesAssoc = [];
        foreach ($paises as $pais) {
            $paisesAssoc[$pais['id_pais']] = $pais['nome'];
        }

        include '../resources/views/pages/consulta.php';
    }

    public function deletar()
    {
        $mensagem = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['email'])) {
            $emailParaDeletar = $_POST['email'];

            $contatoSelecionado = $this->model->buscarPorEmail($emailParaDeletar);

            if ($contatoSelecionado) {
                // Deleta a imagem antes de remover do banco
                if (!empty($contatoSelecionado['imagem'])) {
                    $caminhoImagem = dirname(__DIR__, 2) . '/public/images/' . $contatoSelecionado['imagem'];
                    if (file_exists($caminhoImagem)) {
                        unlink($caminhoImagem); // remove o arquivo da imagem
                    }
                }

                $deletado = $this->model->deletarPorEmail($emailParaDeletar);

                if ($deletado) {
                    $mensagem = "Contato com e-mail {$emailParaDeletar} foi deletado com sucesso.";
                } else {
                    $mensagem = "Erro ao deletar o contato.";
                }
            } else {
                $mensagem = "Contato não encontrado.";
            }
        }

        $contatos = $this->model->contatos();

        include '../resources/views/pages/deletar.php';
    }

    public function sistema() {
        $contatos = $this->model->buscarTodos();
        include '../resources/views/sistema/sistema.php';
    }
}
?>