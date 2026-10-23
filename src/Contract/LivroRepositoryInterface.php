<?php

namespace App\Contract;

use App\Entity\Livro;

interface LivroRepositoryInterface
{
    /** @return Livro|null */
    public function buscarPorTitulo(string $titulo): ?Livro;

    /** @return Livro[] */
    public function listar(): array;

    public function salvar(Livro $livro): void;
    public function excluir(Livro $livro): void;
}
