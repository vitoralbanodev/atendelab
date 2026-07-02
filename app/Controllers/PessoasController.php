<?php
// Controller for `pessoas` table

class PessoasController
{
    private PDO $pdo;

    public function __construct()
    {
        require __DIR__ . '/../../config/database.php';
        $this->pdo = $pdo;
    }

    public function index(): void
    {
        require __DIR__ . '/../Views/pessoas/index.php';
    }

    public function listar(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $sql = 'SELECT id_pessoa, nome, documento, telefone, email, curso, periodo, observacoes, status FROM pessoas ORDER BY id_pessoa DESC';
        $stmt = $this->pdo->query($sql);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function buscarPorId(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) { http_response_code(400); echo json_encode(['erro'=>'ID inválido.']); return; }

        $sql = 'SELECT id_pessoa, nome, documento, telefone, email, curso, periodo, observacoes, status FROM pessoas WHERE id_pessoa = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) { http_response_code(404); echo json_encode(['erro'=>'Pessoa não encontrada.']); return; }
        echo json_encode($row, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function criar(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $nome = trim($_POST['nome'] ?? '');
        $documento = trim($_POST['documento'] ?? null);
        $telefone = trim($_POST['telefone'] ?? null);
        $email = trim($_POST['email'] ?? null);
        $curso = trim($_POST['curso'] ?? null);
        $periodo = trim($_POST['periodo'] ?? null);
        $observacoes = trim($_POST['observacoes'] ?? null);
        $status = trim($_POST['status'] ?? 'ativo');

        if ($nome === '') { http_response_code(400); echo json_encode(['erro'=>'Nome é obrigatório.']); return; }
        if (!in_array($status, ['ativo','inativo'], true)) { http_response_code(400); echo json_encode(['erro'=>'Status inválido.']); return; }
        if ($email !== null && $email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) { http_response_code(400); echo json_encode(['erro'=>'E-mail inválido.']); return; }

        try {
            $sql = 'INSERT INTO pessoas (nome, documento, telefone, email, curso, periodo, observacoes, status)
                    VALUES (:nome, :documento, :telefone, :email, :curso, :periodo, :observacoes, :status)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':documento', $documento);
            $stmt->bindValue(':telefone', $telefone);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':curso', $curso);
            $stmt->bindValue(':periodo', $periodo);
            $stmt->bindValue(':observacoes', $observacoes);
            $stmt->bindValue(':status', $status);
            $stmt->execute();
            http_response_code(201);
            echo json_encode(['mensagem'=>'Pessoa cadastrada.', 'id'=>$this->pdo->lastInsertId()], JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            http_response_code(500); echo json_encode(['erro'=>'Erro ao cadastrar pessoa.']);
        }
    }

    public function atualizar(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $id = filter_input(INPUT_POST, 'id_pessoa', FILTER_VALIDATE_INT);
        $nome = trim($_POST['nome'] ?? '');
        $documento = trim($_POST['documento'] ?? null);
        $telefone = trim($_POST['telefone'] ?? null);
        $email = trim($_POST['email'] ?? null);
        $curso = trim($_POST['curso'] ?? null);
        $periodo = trim($_POST['periodo'] ?? null);
        $observacoes = trim($_POST['observacoes'] ?? null);
        $status = trim($_POST['status'] ?? 'ativo');

        if (!$id || $nome === '') { http_response_code(400); echo json_encode(['erro'=>'ID e nome são obrigatórios.']); return; }
        if (!in_array($status, ['ativo','inativo'], true)) { http_response_code(400); echo json_encode(['erro'=>'Status inválido.']); return; }
        if ($email !== null && $email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) { http_response_code(400); echo json_encode(['erro'=>'E-mail inválido.']); return; }

        try {
            $sql = 'UPDATE pessoas SET nome=:nome, documento=:documento, telefone=:telefone, email=:email, curso=:curso, periodo=:periodo, observacoes=:observacoes, status=:status WHERE id_pessoa = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':documento', $documento);
            $stmt->bindValue(':telefone', $telefone);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':curso', $curso);
            $stmt->bindValue(':periodo', $periodo);
            $stmt->bindValue(':observacoes', $observacoes);
            $stmt->bindValue(':status', $status);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            echo json_encode(['mensagem'=>'Pessoa atualizada.'], JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            http_response_code(500); echo json_encode(['erro'=>'Erro ao atualizar pessoa.']);
        }
    }

    public function inativar(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $id = filter_input(INPUT_POST, 'id_pessoa', FILTER_VALIDATE_INT) ?: filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id) { http_response_code(400); echo json_encode(['erro'=>'ID inválido.']); return; }

        try {
            $sql = 'UPDATE pessoas SET status = "inativo" WHERE id_pessoa = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            echo json_encode(['mensagem'=>'Pessoa inativada.'], JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            http_response_code(500); echo json_encode(['erro'=>'Erro ao inativar pessoa.']);
        }
    }

    public function excluir(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $id = filter_input(INPUT_POST, 'id_pessoa', FILTER_VALIDATE_INT);
        if (!$id) { http_response_code(400); echo json_encode(['erro'=>'ID inválido.']); return; }

        try {
            $sql = 'DELETE FROM pessoas WHERE id_pessoa = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            echo json_encode(['mensagem'=>'Pessoa excluída.'], JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            http_response_code(500); echo json_encode(['erro'=>'Erro ao excluir pessoa.']);
        }
    }
}
