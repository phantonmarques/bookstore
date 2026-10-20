<?php

namespace App\Contract;

interface RelatorioLivroRepositoryInterface
{
    /**
     * @return list<array<string, mixed>>
     */
    public function listarPorAutor(): array;
}
