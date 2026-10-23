<?php

namespace App\Tests\Controller;

use App\Entity\Assunto;
use App\Entity\Autor;
use App\Entity\Livro;
use App\Repository\AssuntoRepository;
use App\Repository\AutorRepository;
use App\Repository\LivroRepository;
use App\Repository\RelatorioLivroRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RelatorioControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private AssuntoRepository $assuntoRepository;
    private AutorRepository $autorRepository;
    private LivroRepository $livroRepository;
    private RelatorioLivroRepository $relatorioLivroRepository;
    private string $path = '/relatorios';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->relatorioLivroRepository = static::getContainer()->get(RelatorioLivroRepository::class);
        $this->assuntoRepository = static::getContainer()->get(AssuntoRepository::class);
        $this->autorRepository = static::getContainer()->get(AutorRepository::class);
        $this->livroRepository = static::getContainer()->get(LivroRepository::class);

        foreach ($this->livroRepository->listar() as $livro) {
            $this->livroRepository->excluir($livro);
        }

        foreach ($this->assuntoRepository->listar() as $assunto) {
            $this->assuntoRepository->excluir($assunto);
        }

        foreach ($this->autorRepository->listar() as $autor) {
            $this->autorRepository->excluir($autor);
        }
    }

    public function testRelatorioPorAutorGerado(): void
    {
        $this->client->request('GET', sprintf('%s/livros-por-autor', $this->path));

        self::assertResponseStatusCodeSame(200);

        self::assertResponseHeaderSame('Content-Type', 'application/pdf');
        self::assertResponseHeaderSame('Content-Disposition', 'attachment; filename=livros-por-autor.pdf');

        $conteudo = $this->client->getResponse()->getContent();

        self::assertIsString($conteudo);
        self::assertStringStartsWith('%PDF-', $conteudo);
    }

    public function testDadosRelatorioPorAutor(): void
    {
        $assunto = new Assunto();
        $assunto->setDescricao('Assunto Teste 1');
        $this->assuntoRepository->salvar($assunto);

        $assunto2 = new Assunto();
        $assunto2->setDescricao('Assunto Teste 2');
        $this->assuntoRepository->salvar($assunto2);

        $autor = new Autor();
        $autor->setNome('Teste Autor 1');
        $this->autorRepository->salvar($autor);

        $livro = new Livro();
        $livro->setTitulo('Teste Livro 1');
        $livro->setEditora('Teste Editora 1');
        $livro->setEdicao(1);
        $livro->setPreco('10.00');
        $livro->setAnoPublicacao('2023');
        $livro->addAssunto($assunto);
        $livro->addAssunto($assunto2);
        $livro->addAutor($autor);
        $this->livroRepository->salvar($livro);

        $dadosRelatorio = $this->relatorioLivroRepository->listarPorAutor();

        foreach ($dadosRelatorio as $registro) {
            self::assertArrayHasKey('autor_id', $registro);
            self::assertArrayHasKey('autor_nome', $registro);
            self::assertArrayHasKey('livro_id', $registro);
            self::assertArrayHasKey('titulo', $registro);
            self::assertArrayHasKey('editora', $registro);
            self::assertArrayHasKey('edicao', $registro);
            self::assertArrayHasKey('ano_publicacao', $registro);
            self::assertArrayHasKey('preco', $registro);
            self::assertArrayHasKey('assuntos', $registro);

            self::assertCount(1, $dadosRelatorio);

            self::assertSame($autor->getId(), (int) $registro['autor_id']);
            self::assertSame($autor->getNome(), (string) $registro['autor_nome']);
            self::assertSame($livro->getId(), (int) $registro['livro_id']);
            self::assertSame($livro->getTitulo(), (string) $registro['titulo']);
            self::assertSame($livro->getEditora(), (string) $registro['editora']);
            self::assertSame($livro->getEdicao(), (int) $registro['edicao']);
            self::assertSame($livro->getAnoPublicacao(), (string) $registro['ano_publicacao']);
            self::assertSame($livro->getPreco(), (string) $registro['preco']);

            $assuntos = [];

            foreach ($livro->getAssuntos() as $assunto) {
                $assuntos[] = $assunto->getDescricao() . ' (#' . $assunto->getId() . ')';
            }

            self::assertSame(implode(', ', $assuntos), (string) $registro['assuntos']);
        }
    }

    public function testDadosRelatorioPorAutorVazio(): void
    {
        $dadosRelatorio = $this->relatorioLivroRepository->listarPorAutor();

        self::assertCount(0, $dadosRelatorio);
    }
}
