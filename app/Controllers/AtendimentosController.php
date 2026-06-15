<?php
// Controller for `atendimentos` table

class AtendimentosController
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

        $sql = 'SELECT id_atendimento, id_tipo_atendimento, id_pessoa, id_usuario, data_atendimento, data_criacao, hora
                FROM atendimentos
                ORDER BY id_atendimento DESC';

        $stmt = $this->pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function buscarPorId(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID inválido.']);
            return;
        }

        $sql = 'SELECT id_atendimento, id_tipo_atendimento, id_pessoa, id_usuario, data_atendimento, data_criacao, hora
                FROM atendimentos
                WHERE id_atendimento = :id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            http_response_code(404);
            echo json_encode(['erro' => 'Atendimento não encontrado.']);
            return;
        }

        echo json_encode($row, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function criar(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id_tipo = filter_input(INPUT_POST, 'id_tipo_atendimento', FILTER_VALIDATE_INT);
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_VALIDATE_INT);
        $id_usuario = filter_input(INPUT_POST, 'id_usuario', FILTER_VALIDATE_INT);
        $data_atendimento = trim($_POST['data_atendimento'] ?? '');
        $hora = trim($_POST['hora'] ?? null);

        if (!$id_tipo || !$id_pessoa || !$id_usuario || $data_atendimento === '') {
            http_response_code(400);
            echo json_encode(['erro' => 'Campos obrigatórios ausentes.']);
            return;
        }

        try {
            $sql = 'INSERT INTO atendimentos (id_tipo_atendimento, id_pessoa, id_usuario, data_atendimento, hora)
                    VALUES (:id_tipo, :id_pessoa, :id_usuario, :data_atendimento, :hora)';

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id_tipo', $id_tipo, PDO::PARAM_INT);
            $stmt->bindValue(':id_pessoa', $id_pessoa, PDO::PARAM_INT);
            $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindValue(':data_atendimento', $data_atendimento);
            $stmt->bindValue(':hora', $hora);
            $stmt->execute();

            http_response_code(201);
            echo json_encode(['mensagem' => 'Atendimento criado.', 'id' => $this->pdo->lastInsertId()], JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao criar atendimento.']);
        }
    }

    public function atualizar(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = filter_input(INPUT_POST, 'id_atendimento', FILTER_VALIDATE_INT);
        $id_tipo = filter_input(INPUT_POST, 'id_tipo_atendimento', FILTER_VALIDATE_INT);
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_VALIDATE_INT);
        $id_usuario = filter_input(INPUT_POST, 'id_usuario', FILTER_VALIDATE_INT);
        $data_atendimento = trim($_POST['data_atendimento'] ?? '');
        $hora = trim($_POST['hora'] ?? null);

        if (!$id || !$id_tipo || !$id_pessoa || !$id_usuario || $data_atendimento === '') {
            http_response_code(400);
            echo json_encode(['erro' => 'Campos obrigatórios ausentes.']);
            return;
        }

        try {
            $sql = 'UPDATE atendimentos
                    SET id_tipo_atendimento = :id_tipo,
                        id_pessoa = :id_pessoa,
                        id_usuario = :id_usuario,
                        data_atendimento = :data_atendimento,
                        hora = :hora
                    WHERE id_atendimento = :id';

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id_tipo', $id_tipo, PDO::PARAM_INT);
            $stmt->bindValue(':id_pessoa', $id_pessoa, PDO::PARAM_INT);
            $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindValue(':data_atendimento', $data_atendimento);
            $stmt->bindValue(':hora', $hora);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(['mensagem' => 'Atendimento atualizado.'], JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao atualizar atendimento.']);
        }
    }

    public function excluir(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = filter_input(INPUT_POST, 'id_atendimento', FILTER_VALIDATE_INT);
        if (!$id) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID inválido.']);
            return;
        }

        try {
            $sql = 'DELETE FROM atendimentos WHERE id_atendimento = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(['mensagem' => 'Atendimento excluído.'], JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao excluir atendimento.']);
        }
    }
}
