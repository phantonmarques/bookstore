<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261020123944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Cria a view do relatório de livros por autor.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE VIEW vw_livros_por_autor AS
            SELECT
                autor."CodAu" AS autor_id,
                autor."Nome" AS autor_nome,
                livro."Codl" AS livro_id,
                livro."Titulo" AS titulo,
                livro."Editora" AS editora,
                livro."Edicao" AS edicao,
                livro."AnoPublicacao" AS ano_publicacao,
                livro."Preco" AS preco,
                COALESCE(temas.assuntos, 'Sem assunto') AS assuntos
            FROM "Autor" autor
            INNER JOIN "Livro_Autor" vinculo
                ON vinculo."Autor_CodAu" = autor."CodAu"
            INNER JOIN "Livro" livro
                ON livro."Codl" = vinculo."Livro_Codl"
            LEFT JOIN (
                SELECT
                    vinculo_assunto."Livro_Codl" AS livro_id,
                    STRING_AGG(
                        assunto."Descricao" || ' (#' || assunto."codAs"::text || ')',
                        ', ' ORDER BY assunto."Descricao", assunto."codAs"
                    ) AS assuntos
                FROM "Livro_Assunto" vinculo_assunto
                INNER JOIN "Assunto" assunto
                    ON assunto."codAs" = vinculo_assunto."Assunto_codAs"
                GROUP BY vinculo_assunto."Livro_Codl"
            ) temas ON temas.livro_id = livro."Codl"
            SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP VIEW vw_livros_por_autor');
    }
}
