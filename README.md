# Seguro Viagem - Motor de Cotação

Projeto desenvolvido como teste técnico para vaga de Desenvolvedor Full Stack Pleno utilizando Laravel e React.

# Instalação do Backend (Laravel)

## 1. Clonar o repositório

```bash
git clone https://github.com/rhenanteix/Motor-de-cotacao.git

cd Motor-de-cotacao
```

## 2. Instalar dependências

```bash
composer install
```

## 3. Configurar variáveis de ambiente

Copie o arquivo de exemplo:

### Linux / MacOS

```bash
cp .env.example .env
```

### Windows

```bash
copy .env.example .env
```

## 4. Gerar a chave da aplicação

```bash
php artisan key:generate
```

## 5. Configurar banco de dados SQLite

Criar o arquivo do banco:

### Linux / MacOS

```bash
touch database/database.sqlite
```

### Windows

```bash
type nul > database/database.sqlite
```

Editar o arquivo `.env`:

```env
DB_CONNECTION=sqlite
```

## 6. Executar as migrations

```bash
php artisan migrate
```

Resultado esperado:

```txt
Migrated successfully.
```

## 7. Executar os testes

```bash
php artisan test
```

Todos os testes devem passar com sucesso.

## 8. Iniciar a API

```bash
php artisan serve
```

A API ficará disponível em:

```txt
http://127.0.0.1:8000
```

### Endpoints disponíveis

Criar cotação:

```http
POST /api/quotes
```

Listar histórico:

```http
GET /api/quotes
```

Consultar detalhes:

```http
GET /api/quotes/{id}
```
