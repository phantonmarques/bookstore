<?php

namespace App\Repository;

use App\Contract\RelatorioLivroRepositoryInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final readonly class RelatorioLivroRepository implements RelatorioLivroRepositoryInterface
{
    public function __construct(private Connection $connection) {}

    /**
     * @throws Exception
     */
    public function listarPorAutor(): array
    {
        return $this->connection->fetchAllAssociative(<<<'SQL'
            SELECT
                autor_id,
                autor_nome,
                livro_id,
                titulo,
                editora,
                edicao,
                ano_publicacao,
                preco,
                assuntos
            FROM vw_livros_por_autor
            ORDER BY autor_nome, autor_id, titulo, livro_id
            SQL
        );
    }
}
