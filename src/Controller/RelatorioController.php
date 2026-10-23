<?php

namespace App\Controller;

use App\Service\RelatorioLivroService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;

final class RelatorioController extends AbstractController
{
    public function __construct(private readonly LoggerInterface $logger) {}

    #[Route('/relatorios/livros-por-autor', name: 'app_relatorio_livros_por_autor', methods: ['GET'])]
    public function livrosPorAutor(RelatorioLivroService $service): Response {
        try {
            return new Response($service->gerar(), Response::HTTP_OK, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => HeaderUtils::makeDisposition(
                    'attachment',
                    'livros-por-autor.pdf',
                ),
                'Cache-Control' => 'private, no-store',
            ]);

        } catch (\Throwable $e) {

            $message = 'Erro ao gerar o relatório de livros por autor';

            $this->logger->error($message, ['exception' => $e]);

            $this->addFlash('error', $message);

            return $this->redirectToRoute('app_livro_index', [], Response::HTTP_SEE_OTHER);
        }
    }
}
