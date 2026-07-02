<?php
require_once __DIR__ . '/app/Controllers/AuthController.php';
require_once __DIR__ . '/app/Controllers/UsuariosController.php';
require_once __DIR__ . '/app/Controllers/AtendimentosController.php';
require_once __DIR__ . '/app/Controllers/EnderecoController.php';
require_once __DIR__ . '/app/Controllers/PessoasController.php';
require_once __DIR__ . '/app/Controllers/TipoAtendimentosController.php';
require_once __DIR__ . '/app/Controllers/DashboardController.php';
require_once __DIR__ . '/app/Middleware/auth.php';

$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? ($controller === 'auth' ? 'login' : 'index');

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
    case 'tipoatendimentos':
    case 'tipos':
    case 'tipos-atendimentos':
        $instance = new TipoAtendimentosController();
        break;

    case 'dashboard':
        $instance = new DashboardController();
        break;

    default:
        $instance = null;
}

if ($instance === null) {
    http_response_code(404);
    echo 'Controlador não encontrado.';
    exit;
}

    switch ($action) {
        case 'listar':
            $instance->listar();
            break;

        case 'buscar':
            $instance->buscarPorId();
            break;

        case 'criar':
            $instance->criar();
            break;

        case 'atualizar':
            $instance->atualizar();
            break;

        case 'inativar':
            if (method_exists($instance, 'inativar')) {
                $instance->inativar();
                break;
            }
            // fall through to default if the method is not implemented

        case 'ativar':
            if (method_exists($instance, 'ativar')) {
                $instance->ativar();
                break;
            }
            // fall through to default if the method is not implemented

        case 'alterarStatus':
            if (method_exists($instance, 'alterarStatus')) {
                $instance->alterarStatus();
                break;
            }
            // fall through to default if the method is not implemented

        case 'excluir':
            $instance->excluir();
            break;

        case 'index':
            if (method_exists($instance, 'index')) {
                exigirAutenticacao();
                $instance->index();
                break;
            }
            // fall through to default if the method is not implemented

        default:
            http_response_code(404);
            echo 'Ação não encontrada para o controlador ' . htmlspecialchars($controller);
            break;
    }