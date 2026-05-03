# Locadora Rota Livre

Aplicacao web em Laravel para gerenciamento simples de uma locadora de carros.

## Funcionalidades

- Dashboard com resumo de clientes ativos, veiculos, locacoes ativas, disponibilidade e receita finalizada.
- CRUD de clientes.
- CRUD de veiculos.
- CRUD de locacoes.
- Busca nas listagens de clientes, veiculos e locacoes.
- Validacoes de cadastro, datas, placa, valores e relacionamentos.
- Dados iniciais para demonstracao usando seeders.

## Modelagem

Entidades principais:

- `clientes`: dados do motorista, CPF, CNH, contato e status.
- `veiculos`: dados da frota, placa, categoria, diaria e status.
- `locacoes`: vincula um cliente a um veiculo em um periodo, com status e valores.

Relacionamentos:

- Um cliente possui muitas locacoes.
- Um veiculo possui muitas locacoes.
- Uma locacao pertence a um cliente e a um veiculo.

## Regras de negocio

- Nao e possivel excluir cliente ou veiculo que ja tenha locacoes vinculadas.
- Um veiculo nao pode ter duas locacoes ativas no mesmo periodo.
- Um cliente pode ter no maximo 2 locacoes ativas.
- Veiculos em manutencao ou inativos nao podem ser locados.
- Locacoes em andamento ou finalizadas nao podem ser excluidas.

## Requisitos

- PHP 8.3 ou superior.
- Composer.
- SQLite habilitado no PHP.

## Setup e execucao

1. Instale as dependencias PHP:

```bash
composer install
```

2. Crie o arquivo `.env` se ele ainda nao existir:

```bash
cp .env.example .env
```

No Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

3. Gere a chave da aplicacao:

```bash
php artisan key:generate
```

4. Crie o banco SQLite caso necessario:

```bash
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
```

5. Execute migrations e seeders:

```bash
php artisan migrate --seed
```

6. Inicie o servidor local:

```bash
php artisan serve
```

Acesse `http://127.0.0.1:8000`.

## Testes

Para executar os testes automatizados:

```bash
php artisan test
```
