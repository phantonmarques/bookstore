<?php

namespace App\Contract;

use App\Entity\Livro;

interface LivroRepositoryInterface
{
    /** @return Livro[] */
    public function listar(): array;

    public function salvar(Livro $livro): void;
    public function excluir(Livro $livro): void;
}
