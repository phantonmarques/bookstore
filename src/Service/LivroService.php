<?php

namespace App\Service;

use App\Contract\LivroRepositoryInterface;
use App\Entity\Livro;

readonly class LivroService
{
    public function __construct(private LivroRepositoryInterface $livroRepository) {}

    public function listar(): array
    {
        return $this->livroRepository->listar();
    }

    public function cadastrar(Livro $livro): void
    {
        $this->livroRepository->salvar($livro);
    }

    public function atualizar(Livro $livro): void
    {
        $this->livroRepository->salvar($livro);
    }

    public function excluir(Livro $livro): void
    {
        $this->livroRepository->excluir($livro);
    }
}
