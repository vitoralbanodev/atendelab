<?php

$titulo='Página: Atendimentos';
require __DIR__ . '/../../layouts/header.php';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
  <div>
    <h1 class="h3 mb-1">Atendimentos</h1>
    <p class="text-secondary mb-0">
      Registro e acompanhamento dos atendimentos acadêmicos.
    </p>
  </div>

  <button class="btn btn-success" type="button" onclick="novoAtendimento()">
    Novo atendimento
  </button>
</div>

<div id="alertas"></div>

<div class="card border-0 shadow-sm mb-4 d-none" id="cardFormulario">
  <div class="card-body">
    <h2 class="h5">Novo atendimento</h2>
    <p class="text-secondary mb-0">
      Registre e acompanhamento dos atendimentos acadêmicos.
    </p>
  </div>

  <form id="formAtendimentos">
    <div class="row g-3">

      <div class="col-md-6">
        <label class="form-label">Pessoa </label>
        <select
          class="form-select"
          name="pessoa_id"
          id="pessoaSelect"
          required
        ></select>
      </div>

      <div class="col-md-6">
        <label class="form-label">Tipo </label>
        <select
          class="form-select"
          name="tipo_atendimento_id"
          id="tipoSelect"
          required
        ></select>
      </div>

      <div class="col-md-4">
        <label class="form-label">Data </label>
        <input
          class="form-control"
          type="date"
          name="data_atendimento"
          required
        />
      </div>

      <div class="col-md-4">
        <label class="form-label">Horário </label>
        <input
          class="form-control"
          type="time"
          name="horario_atendimento"
          required
        />
      </div>

      <div class="col-12">
        <label class="form-label">Observação inicial</label>
        <textarea
          class="form-control"
          name="observacao_final"
          rows="3"
          placeholder="Use este campo para anotações iniciais"></textarea>
      </div>

    </div>

    <div class="modal-footer">
      <button
        type="button"
        class="btn btn-outline-secondary"
        onclick="fecharFormulario()"
      >
        Cancelar
      </button>

      <button class="btn btn-success" type="submit">
        Salvar
      </button>
    </div>
  </form>
</div>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Pessoa</th>
          <th>Tipo</th>
          <th>Responsável</th>
          <th>Data</th>
          <th>Status</th>
          <th class="text-end">Ações</th>
        </tr>
      </thead>

      <tbody id="tabelaAtendimentos">
        <tr>
          <td colspan="7" class="text-center py-4">
            Nenhum atendimento registrado.
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<div class="modal fade" id="modalStatus" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h2 class="modal-title fs-5">Alterar status</h2>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
        ></button>
      </div>

      <form id="formStatus">
        <div class="modal-body">

          <input type="hidden" name="id" id="statusId">

          <div class="mb-1">
            <label class="form-label">Novo status</label>
            <select class="form-select" name="status" required>
              <option value="">Selecione</option>
              <option value="aberto">Aberto</option>
              <option value="em_andamento">Em andamento</option>
              <option value="concluido">Concluído</option>
            </select>
          </div>

          <div>
            <label class="form-label">Observação final</label>
            <textarea
              class="form-control"
              name="observacao_final"
              rows="3"
              placeholder="Obrigatório ao concluir"></textarea>
          </div>

        </div>

        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-outline-secondary"
            data-bs-dismiss="modal"
          >
            Cancelar
          </button>

          <button class="btn btn-success" type="submit">
            Salvar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  const formAtendimentos = document.getElementById('formAtendimentos');
  const tabelaAtendimentos = document.getElementById('tabelaAtendimentos');
  const cardFormulario = document.getElementById('cardFormulario');

  const statusModal = () => {
    return bootstrap.Modal.getOrCreateInstance(
      document.getElementById('modalStatus')
    );
  };

  function novoAtendimento() {
    cardFormulario.classList.remove('d-none');
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  function fecharFormulario() {
    cardFormulario.classList.add('d-none');
    formAtendimentos.reset();
  }

  function labelRegistro(obj, ...keys) {
    for (const key of keys) {
      if (obj[key] !== undefined && obj[key] !== null) {
        return obj[key];
      }
    }
    return '';
  }

  function formatStatus(status) {
    if (status === 'concluido') return 'Concluído';
    if (status === 'em_andamento') return 'Em andamento';
    if (status === 'aberto') return 'Aberto';
    return status || 'N/A';
  }

  function statusBadge(status) {
    if (status === 'concluido') return 'badge bg-success';
    if (status === 'em_andamento') return 'badge bg-warning text-dark';
    return 'badge bg-secondary';
  }

  async function carregarCombos() {
    try {
      const [pessoasResp, tiposResp] = await Promise.all([
        AtendeLabApi.get('pessoas', 'listar'),
        AtendeLabApi.get('tipo_atendimentos', 'listar')
      ]);

      const pessoas = AtendeLabApi.toList(pessoasResp);
      const tipos = AtendeLabApi.toList(tiposResp);

      document.getElementById('pessoaSelect').innerHTML =
        '<option value="">Selecione</option>' +
        pessoas.map(pessoa =>
          `<option value="${Number(pessoa.id_pessoa)}">${AtendeLabApi.escape(pessoa.nome)}</option>`
        ).join('');

      document.getElementById('tipoSelect').innerHTML =
        '<option value="">Selecione</option>' +
        tipos.map(tipo =>
          `<option value="${Number(tipo.id_tipo_atendimento)}">${AtendeLabApi.escape(tipo.nome)}</option>`
        ).join('');
    } catch (error) {
      AtendeLabApi.showAlert('alertas', error.message, 'danger');
    }
  }

  async function carregarAtendimentos() {
    try {
      const resposta = await AtendeLabApi.get('atendimentos', 'listar');
      const atendimentos = AtendeLabApi.toList(resposta);

      if (!atendimentos.length) {
        tabelaAtendimentos.innerHTML = `
          <tr>
            <td colspan="7" class="text-center py-4">Nenhum atendimento registrado.</td>
          </tr>`;
        return;
      }

      tabelaAtendimentos.innerHTML = atendimentos.map(atendimento => {
        const id = atendimento.id ?? atendimento.id_atendimento;
        const pessoa = labelRegistro(atendimento, 'pessoa', 'nome_pessoa', 'nome');
        const tipo = labelRegistro(atendimento, 'tipo', 'nome_tipo', 'tipo');
        const responsavel = labelRegistro(atendimento, 'responsavel', 'usuario', 'nome_usuario');
        const status = atendimento.status ?? 'aberto';

        return `
          <tr>
            <td>${AtendeLabApi.escape(id)}</td>
            <td>${AtendeLabApi.escape(pessoa)}</td>
            <td>${AtendeLabApi.escape(tipo)}</td>
            <td>${AtendeLabApi.escape(responsavel)}</td>
            <td>${AtendeLabApi.escape(atendimento.data_atendimento)} ${AtendeLabApi.escape(atendimento.hora)}</td>
            <td><span class="${statusBadge(status)}">${AtendeLabApi.escape(formatStatus(status))}</span></td>
            <td class="text-end">
              <button type="button" class="btn btn-sm btn-outline-primary" onclick="abrirStatus(${Number(id)}, '${AtendeLabApi.escapeAttr(status)}')">Alterar status</button>
            </td>
          </tr>`;
      }).join('');
    } catch (error) {
      AtendeLabApi.showAlert('alertas', error.message, 'danger');
    }
  }

  function abrirStatus(id, status) {
    document.getElementById('statusId').value = id;
    document.querySelector('#formStatus [name="status"]').value = status || 'aberto';
    statusModal().show();
  }

  formAtendimentos.addEventListener('submit', async event => {
    event.preventDefault();

    try {
      await AtendeLabApi.post('atendimentos', 'criar', new FormData(formAtendimentos));
      AtendeLabApi.showAlert('alertas', 'Atendimento registrado com sucesso.', 'success');
      fecharFormulario();
      await carregarAtendimentos();
    } catch (error) {
      AtendeLabApi.showAlert('alertas', error.message, 'danger');
    }
  });

  document.getElementById('formStatus').addEventListener('submit', async event => {
    event.preventDefault();

    try {
      await AtendeLabApi.post('atendimentos', 'alterarStatus', new FormData(event.target));
      statusModal().hide();
      AtendeLabApi.showAlert('alertas', 'Status atualizado com sucesso.', 'success');
      await carregarAtendimentos();
    } catch (error) {
      AtendeLabApi.showAlert('alertas', error.message, 'danger');
    }
  });

  document.addEventListener('DOMContentLoaded', async () => {
    await carregarCombos();
    await carregarAtendimentos();
  });
</script>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
