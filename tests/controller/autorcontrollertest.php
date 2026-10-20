<?php

namespace App\Tests\Controller;

use App\Entity\Autor;
use App\Repository\AutorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AutorControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private AutorRepository $autorRepository;
    private string $path = '/autor/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->autorRepository = static::getContainer()->get(AutorRepository::class);

        foreach ($this->autorRepository->listar() as $autor) {
            $this->manager->remove($autor);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Lista de Autores');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
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
        $fixture = new Autor();
        $fixture->setNome('Teste Autor 1');

        $this->autorRepository->salvar($fixture);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Autor');
    }

    public function testEdit(): void
    {
        $fixture = new Autor();
        $fixture->setNome('Teste Autor 1');

        $this->autorRepository->salvar($fixture);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Salvar', [
            'autor[nome]' => 'Teste Autor 1 Editado',
        ]);

        self::assertResponseRedirects('/autor');

        $autores = $this->autorRepository->listar();

        self::assertSame('Teste Autor 1 Editado', $autores[0]->getNome());
    }

    public function testRemove(): void
    {
        $fixture = new Autor();
        $fixture->setNome('Teste Autor 1');

        $this->autorRepository->salvar($fixture);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Excluir');

        self::assertResponseRedirects('/autor');
        self::assertCount(0, $this->autorRepository->listar());
    }
}
