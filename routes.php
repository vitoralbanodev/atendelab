<?php
// Carrega o controller responsável pelos endpoints de usuários.
// Observação: o arquivo no projeto está no singular (UsuarioController.php).
require_once __DIR__ . '/app/Controllers/UsuariosController.php';
require_once __DIR__ . '/app/Controllers/AtendimentosController.php';
require_once __DIR__ . '/app/Controllers/EnderecoController.php';
require_once __DIR__ . '/app/Controllers/PessoasController.php';
require_once __DIR__ . '/app/Controllers/TipoAtendimentosController.php';

// Define controller e action por query string.
// Exemplo: ?controller=usuarios&action=listar
$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

// Roteador simples que instância controllers suportados e chama ações CRUD.
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

if ($instance !== null) {
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
            echo 'Ação não encontrada para o controller informado.';
            break;
    }
} else {
    // Resposta básica para indicar que a aplicação está no ar.
    echo '<h1>AtendeLab</h1>';
    echo '<p>Projeto em execução. Use ?controller=usuarios&action=listar para testar.</p>';
}