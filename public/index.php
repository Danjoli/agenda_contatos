<?php
include '../resources/includes/sidebar.php'; 
include '../resources/includes/header.php';
include '../resources/includes/footer.php';

require_once '../app/Controllers/AgendaController.php';
require_once '../app/Requests/ContatoRequest.php';

use App\Controllers\AgendaController;
use App\Requests\ContatoValiador;

$controller = new AgendaController();
$caminho = $_GET['pagina'] ?? 'home';

switch ($caminho) {
    case 'home':
        $controller->home();
        break;

    case 'adicionar':
        $controller->adicionar(); // Formulário + processamento de cadastro
        break;
    
    case 'store':
        $controller->store();
        break;

    case 'editar':
        $controller->editar();
        break;
    
    case 'update':
        $controller->update();
        break;

    case 'consulta':
        $controller->consulta();
        break;

    case 'deletar':
        $controller->deletar();
        break;

    case 'sistema':
        $controller->sistema();
        break;
        
    default:
        echo "<h2>Página não encontrada.</h2>";
        break;
};

    
