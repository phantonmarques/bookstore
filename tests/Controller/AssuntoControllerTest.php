<?php

namespace App\Tests\Controller;

use App\Entity\Assunto;
use App\Repository\AssuntoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AssuntoControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private AssuntoRepository $assuntoRepository;
    private string $path = '/assunto/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->assuntoRepository = static::getContainer()->get(AssuntoRepository::class);

        foreach ($this->assuntoRepository->listar() as $assunto) {
            $this->manager->remove($assunto);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Lista de Assuntos');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
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
        $fixture = new Assunto();
        $fixture->setDescricao('Assunto Teste 1');

        $this->assuntoRepository->salvar($fixture);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Assunto');
    }

    public function testEdit(): void
    {
        $fixture = new Assunto();
        $fixture->setDescricao('Assunto Teste 1');

        $this->assuntoRepository->salvar($fixture);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Salvar', [
            'assunto[descricao]' => 'Assunto Modificado 1',
        ]);

        self::assertResponseRedirects('/assunto');

        $assuntos = $this->assuntoRepository->listar();

        self::assertSame('Assunto Modificado 1', $assuntos[0]->getDescricao());
    }

    public function testRemove(): void
    {
        $fixture = new Assunto();
        $fixture->setDescricao('Assunto Teste 1');

        $this->assuntoRepository->salvar($fixture);

        $this->client->request('GET', rtrim($this->path, '/'));
        $this->client->submitForm('Excluir');

        self::assertResponseRedirects('/assunto');
        self::assertCount(0, $this->assuntoRepository->listar());
    }
}
