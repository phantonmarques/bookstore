<?php

namespace App\Tests\Controller;

use App\Entity\Assunto;
use App\Entity\Autor;
use App\Entity\Livro;
use App\Repository\AssuntoRepository;
use App\Repository\AutorRepository;
use App\Repository\LivroRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LivroControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private AssuntoRepository $assuntoRepository;
    private AutorRepository $autorRepository;
    private LivroRepository $livroRepository;
    private string $path = '/livro/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
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

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Lista de Livros');

        self::assertSame('Lista de Livros', $crawler->filter('section h1')->first()->text());
        self::assertSame('Baixar relatório por autor', $crawler->filter('section a')->first()->text());
    }

    public function testNew(): void
    {
        $assunto = new Assunto();
        $assunto->setDescricao('Assunto Teste 1');
        $this->assuntoRepository->salvar($assunto);

        $autor = new Autor();
        $autor->setNome('Teste Autor 1');
        $this->autorRepository->salvar($autor);

        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Cadastrar', [
            'livro[titulo]' => 'Teste Livro 1',
            'livro[editora]' => 'Teste Editora 1',
            'livro[edicao]' => 1,
            'livro[preco]' => 10.00,
            'livro[anoPublicacao]' => 2023,
            'livro[assuntos]' => [
                (string) $assunto->getId()
            ],
            'livro[autores]' => [
                (string) $autor->getId()
            ],
        ]);

        self::assertResponseRedirects('/livro');

        self::assertCount(1, $this->livroRepository->listar());

        $livroCadastrado = $this->livroRepository->buscarPorTitulo('Teste Livro 1');
        self::assertNotNull($livroCadastrado);
        self::assertSame('Teste Editora 1', $livroCadastrado->getEditora());
        self::assertSame(1, $livroCadastrado->getEdicao());
        self::assertSame('2023', $livroCadastrado->getAnoPublicacao());
        self::assertSame('10.00', $livroCadastrado->getPreco());
        self::assertCount(1, $livroCadastrado->getAssuntos());
        self::assertCount(1, $livroCadastrado->getAutores());
    }

    public function testShow(): void
    {
        $assunto = new Assunto();
        $assunto->setDescricao('Assunto Teste 1');
        $this->assuntoRepository->salvar($assunto);

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
        $livro->addAutor($autor);
        $this->livroRepository->salvar($livro);

        $this->client->request('GET', sprintf('%s%s', $this->path, $livro->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Livro');
    }

    public function testEdit(): void
    {
        $assunto = new Assunto();
        $assunto->setDescricao('Assunto Teste 1');
        $this->assuntoRepository->salvar($assunto);

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
        $livro->addAutor($autor);
        $this->livroRepository->salvar($livro);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $livro->getId()));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Salvar', [
            'livro[titulo]' => 'Teste Livro 1 Editado',
            'livro[editora]' => 'Teste Editora 1 Editada',
            'livro[edicao]' => 2,
            'livro[preco]' => 20.00,
            'livro[anoPublicacao]' => 2024,
            'livro[assuntos]' => [
                (string) $assunto->getId()
            ],
            'livro[autores]' => [
                (string) $autor->getId()
            ],
        ]);

        self::assertResponseRedirects('/livro');

        $livros = $this->livroRepository->listar();

        self::assertSame('Teste Livro 1 Editado', $livros[0]->getTitulo());
    }

    public function testRemove(): void
    {
        $assunto = new Assunto();
        $assunto->setDescricao('Assunto Teste 1');
        $this->assuntoRepository->salvar($assunto);

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
        $livro->addAutor($autor);
        $this->livroRepository->salvar($livro);

        $crawler = $this->client->request('GET', rtrim($this->path, '/'));

        self::assertResponseStatusCodeSame(200);

        $form = $crawler
            ->filter(sprintf('form[action="/livro/%s"]', $livro->getId()))
            ->form();

        $this->client->submit($form);

        self::assertResponseRedirects('/livro');
        self::assertCount(0, $this->livroRepository->listar());
    }
}
