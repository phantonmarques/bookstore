<?php

namespace App\Controller;

use App\Entity\Autor;
use App\Service\AutorService;
use App\Form\AutorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/autor')]
final class AutorController extends AbstractController
{
    public function __construct(private readonly AutorService $autorService) {}

    #[Route(name: 'app_autor_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('autor/index.html.twig', [
            'autores' => $this->autorService->listar(),
        ]);
    }

    #[Route('/new', name: 'app_autor_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $autor = new Autor();

        $form = $this->createForm(AutorType::class, $autor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->autorService->cadastrar($autor);

            $this->addFlash('success', 'Autor cadastrado com sucesso!');

            return $this->redirectToRoute('app_autor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('autor/new.html.twig', [
            'autor' => $autor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_autor_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Autor $autor): Response
    {
        return $this->render('autor/show.html.twig', [
            'autor' => $autor,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_autor_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, Autor $autor): Response
    {
        $form = $this->createForm(AutorType::class, $autor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->autorService->atualizar($autor);

            $this->addFlash('success', 'Autor atualizado com sucesso!');

            return $this->redirectToRoute('app_autor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('autor/edit.html.twig', [
            'autor' => $autor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_autor_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, Autor $autor): Response
    {
        $token = $request->getPayload()->getString('_token');

        if ($this->isCsrfTokenValid('delete' . $autor->getId(), $token)) {
            $this->autorService->excluir($autor);

            $this->addFlash('success', 'Autor excluído com sucesso!');
        } else {
            $this->addFlash('error', 'Não foi possível realizar a exclusão, tente novamente!');
        }

        return $this->redirectToRoute('app_autor_index', [], Response::HTTP_SEE_OTHER);
    }
}
