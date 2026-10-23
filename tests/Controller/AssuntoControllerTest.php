<?php

namespace App\Tests\Controller;

use App\Entity\Assunto;
use App\Repository\AssuntoRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AssuntoControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private AssuntoRepository $assuntoRepository;
    private string $path = '/assunto/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->assuntoRepository = static::getContainer()->get(AssuntoRepository::class);

        foreach ($this->assuntoRepository->listar() as $assunto) {
            $this->assuntoRepository->excluir($assunto);
        }
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Lista de Assuntos');

        self::assertSame('Lista de Assuntos', $crawler->filter('section h1')->first()->text());
        self::assertSame('Novo Assunto', $crawler->filter('section a')->first()->text());
    }

    public function testNew(): void
    {
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Cadastrar', [
            'assunto[descricao]' => 'Assunto Teste 1',
        ]);

        self::assertResponseRedirects('/assunto');

        self::assertCount(1, $this->assuntoRepository->listar());
    }

    public function testShow(): void
    {
        $assunto = new Assunto();
        $assunto->setDescricao('Assunto Teste 1');

        $this->assuntoRepository->salvar($assunto);

        $this->client->request('GET', sprintf('%s%s', $this->path, $assunto->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Assunto');
    }

    public function testEdit(): void
    {
        $assunto = new Assunto();
        $assunto->setDescricao('Assunto Teste 1');

        $this->assuntoRepository->salvar($assunto);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $assunto->getId()));

        $this->client->submitForm('Salvar', [
            'assunto[descricao]' => 'Assunto Modificado 1',
        ]);

        self::assertResponseRedirects('/assunto');

        $assuntos = $this->assuntoRepository->listar();

        self::assertSame('Assunto Modificado 1', $assuntos[0]->getDescricao());
    }

    public function testRemove(): void
    {
        $assunto = new Assunto();
        $assunto->setDescricao('Assunto Teste 1');

        $this->assuntoRepository->salvar($assunto);

        $this->client->request('GET', rtrim($this->path, '/'));
        $this->client->submitForm('Excluir');

        self::assertResponseRedirects('/assunto');
        self::assertCount(0, $this->assuntoRepository->listar());
    }
}
