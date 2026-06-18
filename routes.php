<?php
require_once __DIR__ . '/app/Controllers/AuthController.php';
require_once __DIR__ . '/app/Controllers/UsuariosController.php';
require_once __DIR__ . '/app/Controllers/AtendimentosController.php';
require_once __DIR__ . '/app/Controllers/EnderecoController.php';
require_once __DIR__ . '/app/Controllers/PessoasController.php';
require_once __DIR__ . '/app/Controllers/TipoAtendimentosController.php';
require_once __DIR__ . '/app/Middleware/auth.php';

$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

if ($controller === 'auth') {
    $authController = new AuthController();
    switch ($action) {
        case 'login':
            $authController->exibirLogin();
            break;

        case 'entrar':
            $authController->entrar();
            break;

        case 'dashboard':
            exigirAutenticacao();
            $authController->dashboard();
            break;

        case 'logout':
            $authController->logout();
            break;

        default:
            http_response_code(404);
            echo 'Ação de autenticação não encontrada.';
            break;
    }

    exit;
}

$instance = null;
switch ($controller) {
    case 'usuarios':
        $instance = new UsuariosController();
        break;

    case 'atendimentos':
        $instance = new AtendimentosController();
        break;

    case 'endereco':
        $instance = new EnderecoController();
        break;

    case 'pessoas':
        $instance = new PessoasController();
        break;

    case 'tipo_atendimentos':
        $instance = new TipoAtendimentosController();
        break;

    default:
        $instance = null;
}

    switch ($action) {
        case 'listar':
            $instance->listar();
            break;

        case 'buscar':
            // padrão: buscar por ID via GET
            $instance->buscarPorId();
            break;

        case 'criar':
            $instance->criar();
            break;

        case 'atualizar':
            $instance->atualizar();
            break;

        case 'excluir':
            $instance->excluir();
            break;

        default:
            http_response_code(404);
            echo 'Ação não encontrada para o controlador ' . htmlspecialchars($controller);
            break;
    }