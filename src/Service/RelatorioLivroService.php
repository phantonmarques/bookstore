<?php

declare(strict_types=1);

namespace App\Service;

use App\Contract\GeradorPdfInterface;
use App\Contract\RelatorioLivroRepositoryInterface;
use Twig\Environment;

final class RelatorioLivroService
{
    public function __construct(
        private readonly RelatorioLivroRepositoryInterface $repository,
        private readonly GeradorPdfInterface $geradorPdf,
        private readonly Environment $twig,
    ) {}

    public function gerar(): string
    {
        try {
            $autores = [];

            foreach ($this->repository->listarPorAutor() ?? [] as $registro) {
                $autorId = (int) $registro['autor_id'];

                if (!isset($autores[$autorId])) {
                    $autores[$autorId] = [
                        'id' => $autorId,
                        'nome' => $registro['autor_nome'],
                        'livros' => [],
                    ];
                }

                $autores[$autorId]['livros'][] = [
                    'id' => $registro['livro_id'],
                    'titulo' => $registro['titulo'],
                    'editora' => $registro['editora'],
                    'edicao' => $registro['edicao'],
                    'ano_publicacao' => $registro['ano_publicacao'],
                    'preco' => $registro['preco'],
                    'assuntos' => $registro['assuntos'],
                ];
            }

            $html = $this->twig->render(
                'relatorio/livros_por_autor.html.twig',
                [
                    'autores' => array_values($autores),
                    'geradoEm' => date('d/m/Y H:i:s'),
                ],
            );

            return $this->geradorPdf->gerar($html);

        } catch (\Throwable $e) {
            throw new \RuntimeException('Erro ao gerar o relatório de livros por autor.', 0, $e);
        }
    }
}
