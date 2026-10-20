<?php

namespace App\Controller;

use App\Service\RelatorioLivroService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RelatorioController extends AbstractController
{
    #[Route('/relatorios/livros-por-autor', name: 'app_relatorio_livros_por_autor', methods: ['GET'])]
    public function livrosPorAutor(RelatorioLivroService $service): Response {
        return new Response($service->gerar(), Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => HeaderUtils::makeDisposition(
                'attachment',
                'livros-por-autor.pdf',
            ),
            'Cache-Control' => 'private, no-store',
        ]);
    }
}
