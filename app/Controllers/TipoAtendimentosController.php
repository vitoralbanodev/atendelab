<?php
// Controller for `tipo_atendimentos` table

class TipoAtendimentosController
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
        $sql = 'SELECT id_tipo_atendimento, nome, descricao, ativo, data_criacao FROM tipo_atendimentos ORDER BY id_tipo_atendimento DESC';
        $stmt = $this->pdo->query($sql);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function buscarPorId(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) { http_response_code(400); echo json_encode(['erro'=>'ID inválido.']); return; }

        $sql = 'SELECT id_tipo_atendimento, nome, descricao, ativo, data_criacao FROM tipo_atendimentos WHERE id_tipo_atendimento = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) { http_response_code(404); echo json_encode(['erro'=>'Tipo de atendimento não encontrado.']); return; }
        echo json_encode($row, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function criar(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $nome = trim($_POST['nome'] ?? '');
        $descricao = trim($_POST['descricao'] ?? null);
        $ativo = isset($_POST['ativo']) ? (int) $_POST['ativo'] : 1;

        if ($nome === '') { http_response_code(400); echo json_encode(['erro'=>'Nome é obrigatório.']); return; }

        if (!in_array($ativo, [0,1], true)) { http_response_code(400); echo json_encode(['erro'=>'Valor inválido para ativo.']); return; }

        try {
            $sql = 'INSERT INTO tipo_atendimentos (nome, descricao, ativo) VALUES (:nome, :descricao, :ativo)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':descricao', $descricao);
            $stmt->bindValue(':ativo', $ativo, PDO::PARAM_INT);
            $stmt->execute();
            http_response_code(201);
            echo json_encode(['mensagem'=>'Tipo de atendimento cadastrado.', 'id'=>$this->pdo->lastInsertId()], JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            http_response_code(500); echo json_encode(['erro'=>'Erro ao cadastrar tipo de atendimento.']);
        }
    }

    public function atualizar(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $id = filter_input(INPUT_POST, 'id_tipo_atendimento', FILTER_VALIDATE_INT);
        $nome = trim($_POST['nome'] ?? '');
        $descricao = trim($_POST['descricao'] ?? null);
        $ativo = isset($_POST['ativo']) ? (int) $_POST['ativo'] : 1;

        if (!$id || $nome === '') { http_response_code(400); echo json_encode(['erro'=>'ID e nome são obrigatórios.']); return; }
        if (!in_array($ativo, [0,1], true)) { http_response_code(400); echo json_encode(['erro'=>'Valor inválido para ativo.']); return; }

        try {
            $sql = 'UPDATE tipo_atendimentos SET nome=:nome, descricao=:descricao, ativo=:ativo WHERE id_tipo_atendimento = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':descricao', $descricao);
            $stmt->bindValue(':ativo', $ativo, PDO::PARAM_INT);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            echo json_encode(['mensagem'=>'Tipo de atendimento atualizado.'], JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            http_response_code(500); echo json_encode(['erro'=>'Erro ao atualizar tipo de atendimento.']);
        }
    }

    public function excluir(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $id = filter_input(INPUT_POST, 'id_tipo_atendimento', FILTER_VALIDATE_INT);
        if (!$id) { http_response_code(400); echo json_encode(['erro'=>'ID inválido.']); return; }

        try {
            $sql = 'DELETE FROM tipo_atendimentos WHERE id_tipo_atendimento = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            echo json_encode(['mensagem'=>'Tipo de atendimento excluído.'], JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            http_response_code(500); echo json_encode(['erro'=>'Erro ao excluir tipo de atendimento.']);
        }
    }
}
