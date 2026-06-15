# Test URLs e comandos curl

Substitua `http://localhost/atendelab/public/` conforme necessário e ajuste IDs quando apropriado.


> Observação: links abaixo abrem diretamente os endpoints via navegador (GET). Para operações que requerem `POST` (criar/atualizar/excluir), use `curl` ou formulários HTML; clicar diretamente só funcionará para `GET`.

## Usuários

http://localhost/atendelab/public/?controller=usuarios&action=listar

http://localhost/atendelab/public/?controller=usuarios&action=buscar&id=1

## Atendimentos

http://localhost/atendelab/public/?controller=atendimentos&action=listar

http://localhost/atendelab/public/?controller=atendimentos&action=buscar&id=1

## Endereço

http://localhost/atendelab/public/?controller=endereco&action=listar

http://localhost/atendelab/public/?controller=endereco&action=buscar&id=1

## Pessoas

http://localhost/atendelab/public/?controller=pessoas&action=listar

http://localhost/atendelab/public/?controller=pessoas&action=buscar&id=1

## Tipo Atendimentos

http://localhost/atendelab/public/?controller=tipo_atendimentos&action=listar

http://localhost/atendelab/public/?controller=tipo_atendimentos&action=buscar&id=1

## Atendimentos

- Listar

```bash
curl -i "http://localhost/atendelab/public/?controller=atendimentos&action=listar"
```

- Buscar

```bash
curl -i "http://localhost/atendelab/public/?controller=atendimentos&action=buscar&id=1"
```

- Criar

```bash
curl -i -X POST "http://localhost/atendelab/public/?controller=atendimentos&action=criar" \
  -d "id_tipo_atendimento=1&id_pessoa=1&id_usuario=1&data_atendimento=2026-06-15&hora=09:00:00"
```

- Atualizar

```bash
curl -i -X POST "http://localhost/atendelab/public/?controller=atendimentos&action=atualizar" \
  -d "id_atendimento=1&id_tipo_atendimento=1&id_pessoa=1&id_usuario=1&data_atendimento=2026-06-16&hora=10:00:00"
```

- Excluir

```bash
curl -i -X POST "http://localhost/atendelab/public/?controller=atendimentos&action=excluir" \
  -d "id_atendimento=1"
```

## Endereço

- Listar

```bash
curl -i "http://localhost/atendelab/public/?controller=endereco&action=listar"
```

- Buscar

```bash
curl -i "http://localhost/atendelab/public/?controller=endereco&action=buscar&id=1"
```

- Criar

```bash
curl -i -X POST "http://localhost/atendelab/public/?controller=endereco&action=criar" \
  -d "logradouro=Rua+Exemplo&numero=123&complemento=Apto+1&bairro=Centro&cidade=Cidade&estado=SP&cep=01234567"
```

- Atualizar

```bash
curl -i -X POST "http://localhost/atendelab/public/?controller=endereco&action=atualizar" \
  -d "id_endereco=1&logradouro=Rua+Nova&numero=456&bairro=Centro&cidade=Cidade&estado=SP&cep=01234567"
```

- Excluir

```bash
curl -i -X POST "http://localhost/atendelab/public/?controller=endereco&action=excluir" \
  -d "id_endereco=1"
```

## Pessoas

- Listar

```bash
curl -i "http://localhost/atendelab/public/?controller=pessoas&action=listar"
```

- Buscar

```bash
curl -i "http://localhost/atendelab/public/?controller=pessoas&action=buscar&id=1"
```

- Criar

```bash
curl -i -X POST "http://localhost/atendelab/public/?controller=pessoas&action=criar" \
  -d "nome=Maria&cpf=12345678901&telefone=11999999999&email=maria@example.com&data_nascimento=1990-01-01&id_endereco=1"
```

- Atualizar

```bash
curl -i -X POST "http://localhost/atendelab/public/?controller=pessoas&action=atualizar" \
  -d "id_pessoa=1&nome=Maria+Silva&email=maria2@example.com"
```

- Excluir

```bash
curl -i -X POST "http://localhost/atendelab/public/?controller=pessoas&action=excluir" \
  -d "id_pessoa=1"
```

## Tipo Atendimentos

- Listar

```bash
curl -i "http://localhost/atendelab/public/?controller=tipo_atendimentos&action=listar"
```

- Buscar

```bash
curl -i "http://localhost/atendelab/public/?controller=tipo_atendimentos&action=buscar&id=1"
```

- Criar

```bash
curl -i -X POST "http://localhost/atendelab/public/?controller=tipo_atendimentos&action=criar" \
  -d "nome=Consulta&descricao=Atendimento+geral&ativo=1"
```

- Atualizar

```bash
curl -i -X POST "http://localhost/atendelab/public/?controller=tipo_atendimentos&action=atualizar" \
  -d "id_tipo_atendimento=1&nome=Consulta+Atualizada&ativo=1"
```

- Excluir

```bash
curl -i -X POST "http://localhost/atendelab/public/?controller=tipo_atendimentos&action=excluir" \
  -d "id_tipo_atendimento=1"
```
