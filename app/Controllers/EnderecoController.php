<?php
// Controller for `endereco` table

class EnderecoController
{
    private PDO $pdo;

    public function __construct()
    {
        require __DIR__ . '/../../config/database.php';
        $this->pdo = $pdo;
    }

    public function listar(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $sql = 'SELECT id_endereco, logradouro, numero, complemento, bairro, cidade, estado, cep
                FROM endereco
                ORDER BY id_endereco DESC';
        $stmt = $this->pdo->query($sql);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function buscarPorId(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) { http_response_code(400); echo json_encode(['erro'=>'ID inválido.']); return; }

        $sql = 'SELECT id_endereco, logradouro, numero, complemento, bairro, cidade, estado, cep
                FROM endereco WHERE id_endereco = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) { http_response_code(404); echo json_encode(['erro'=>'Endereço não encontrado.']); return; }
        echo json_encode($row, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function criar(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $logradouro = trim($_POST['logradouro'] ?? '');
        $numero = trim($_POST['numero'] ?? '');
        $complemento = trim($_POST['complemento'] ?? null);
        $bairro = trim($_POST['bairro'] ?? '');
        $cidade = trim($_POST['cidade'] ?? '');
        $estado = trim($_POST['estado'] ?? '');
        $cep = trim($_POST['cep'] ?? '');

        if ($logradouro === '' || $numero === '' || $bairro === '' || $cidade === '' || $estado === '' || $cep === '') {
            http_response_code(400); echo json_encode(['erro'=>'Campos obrigatórios ausentes.']); return;
        }

        if (strlen($estado) !== 2) { http_response_code(400); echo json_encode(['erro'=>'Estado inválido.']); return; }

        try {
            $sql = 'INSERT INTO endereco (logradouro, numero, complemento, bairro, cidade, estado, cep)
                    VALUES (:logradouro, :numero, :complemento, :bairro, :cidade, :estado, :cep)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':logradouro', $logradouro);
            $stmt->bindValue(':numero', $numero);
            $stmt->bindValue(':complemento', $complemento);
            $stmt->bindValue(':bairro', $bairro);
            $stmt->bindValue(':cidade', $cidade);
            $stmt->bindValue(':estado', $estado);
            $stmt->bindValue(':cep', $cep);
            $stmt->execute();
            http_response_code(201);
            echo json_encode(['mensagem'=>'Endereço cadastrado.', 'id'=>$this->pdo->lastInsertId()], JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            http_response_code(500); echo json_encode(['erro'=>'Erro ao cadastrar endereço.']);
        }
    }

    public function atualizar(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $id = filter_input(INPUT_POST, 'id_endereco', FILTER_VALIDATE_INT);
        $logradouro = trim($_POST['logradouro'] ?? '');
        $numero = trim($_POST['numero'] ?? '');
        $complemento = trim($_POST['complemento'] ?? null);
        $bairro = trim($_POST['bairro'] ?? '');
        $cidade = trim($_POST['cidade'] ?? '');
        $estado = trim($_POST['estado'] ?? '');
        $cep = trim($_POST['cep'] ?? '');

        if (!$id || $logradouro === '' || $numero === '' || $bairro === '' || $cidade === '' || $estado === '' || $cep === '') {
            http_response_code(400); echo json_encode(['erro'=>'Campos obrigatórios ausentes.']); return;
        }

        if (strlen($estado) !== 2) { http_response_code(400); echo json_encode(['erro'=>'Estado inválido.']); return; }

        try {
            $sql = 'UPDATE endereco SET logradouro=:logradouro, numero=:numero, complemento=:complemento, bairro=:bairro, cidade=:cidade, estado=:estado, cep=:cep WHERE id_endereco = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':logradouro', $logradouro);
            $stmt->bindValue(':numero', $numero);
            $stmt->bindValue(':complemento', $complemento);
            $stmt->bindValue(':bairro', $bairro);
            $stmt->bindValue(':cidade', $cidade);
            $stmt->bindValue(':estado', $estado);
            $stmt->bindValue(':cep', $cep);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            echo json_encode(['mensagem'=>'Endereço atualizado.'], JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            http_response_code(500); echo json_encode(['erro'=>'Erro ao atualizar endereço.']);
        }
    }

    public function excluir(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $id = filter_input(INPUT_POST, 'id_endereco', FILTER_VALIDATE_INT);
        if (!$id) { http_response_code(400); echo json_encode(['erro'=>'ID inválido.']); return; }

        try {
            $sql = 'DELETE FROM endereco WHERE id_endereco = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            echo json_encode(['mensagem'=>'Endereço excluído.'], JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            http_response_code(500); echo json_encode(['erro'=>'Erro ao excluir endereço.']);
        }
    }
}
