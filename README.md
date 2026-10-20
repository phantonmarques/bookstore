# Bookstore

Aplicação para cadastro, consulta, edição e exclusão de livros, autores e assuntos, com relatório em PDF agrupado por autor e consulta baseada em uma view do PostgreSQL.

Desenvolvida com PHP, Symfony, Doctrine, Twig, Bootstrap e Dompdf. A aplicação utiliza services, Repository e Adapter para separar as responsabilidades.

## Executar

Requisitos: Docker Compose e Composer com uma versão de PHP compatível com o projeto.

Configure a conexão no `.env`:

```dotenv
DATABASE_URL="postgresql://postgres:postgres123@database:5432/bookstore?serverVersion=16&charset=utf8"
```

As credenciais devem corresponder às configuradas no Compose. Depois, execute na raiz do projeto:

```bash
composer install
docker compose up -d --build
docker compose exec php php bin/console importmap:install
docker compose exec php php bin/console doctrine:migrations:migrate
```

Acesse: http://localhost:8080/

Relatório: http://localhost:8080/relatorios/livros-por-autor

O PDF é gerado no momento do download, sem armazenamento permanente do relatório.

## Testes

Na primeira execução, prepare o banco de testes:

```bash
docker compose exec php php bin/console doctrine:database:create --env=test --if-not-exists
docker compose exec php php bin/console doctrine:migrations:migrate --env=test
```

Execute os testes:

```bash
docker compose exec -e APP_ENV=test php php bin/phpunit
```

## Observações

Foram utilizados nomes em português e preservados os nomes das tabelas e colunas apresentados no diagrama. O campo de preço foi acrescentado conforme o requisito adicional. Os nomes dos índices das tabelas associativas foram ajustados nas migrations para acompanhar o diagrama.
