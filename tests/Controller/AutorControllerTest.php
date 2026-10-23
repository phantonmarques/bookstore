<?php

namespace App\Tests\Controller;

use App\Entity\Autor;
use App\Repository\AutorRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AutorControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private AutorRepository $autorRepository;
    private string $path = '/autor/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->autorRepository = static::getContainer()->get(AutorRepository::class);

        foreach ($this->autorRepository->listar() as $autor) {
            $this->autorRepository->excluir($autor);
        }
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Lista de Autores');

        self::assertSame('Lista de Autores', $crawler->filter('section h1')->first()->text());
        self::assertSame('Novo Autor', $crawler->filter('section a')->first()->text());
    }

    public function testNew(): void
    {
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Cadastrar', [
            'autor[nome]' => 'Teste Autor 1',
        ]);

        self::assertResponseRedirects('/autor');

        self::assertCount(1, $this->autorRepository->listar());
    }

    public function testShow(): void
    {
        $autor = new Autor();
        $autor->setNome('Teste Autor 1');

        $this->autorRepository->salvar($autor);

        $this->client->request('GET', sprintf('%s%s', $this->path, $autor->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Autor');
    }

    public function testEdit(): void
    {
        $autor = new Autor();
        $autor->setNome('Teste Autor 1');

        $this->autorRepository->salvar($autor);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $autor->getId()));

        $this->client->submitForm('Salvar', [
            'autor[nome]' => 'Teste Autor 1 Editado',
        ]);

        self::assertResponseRedirects('/autor');

        $autores = $this->autorRepository->listar();

        self::assertSame('Teste Autor 1 Editado', $autores[0]->getNome());
    }

    public function testRemove(): void
    {
        $autor = new Autor();
        $autor->setNome('Teste Autor 1');

        $this->autorRepository->salvar($autor);

        $crawler = $this->client->request('GET', rtrim($this->path, '/'));

        $form = $crawler
            ->filter(sprintf('form[action="/autor/%s"]', $autor->getId()))
            ->form();

        $this->client->submit($form);

        self::assertResponseRedirects('/autor');
        self::assertCount(0, $this->autorRepository->listar());
    }
}
