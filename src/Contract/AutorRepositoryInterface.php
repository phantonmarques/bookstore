<?php

namespace App\Contract;

use App\Entity\Autor;

interface AutorRepositoryInterface
{
    /** @return Autor[] */
    public function listar(): array;

    public function salvar(Autor $autor): void;
    public function excluir(Autor $autor): void;
}
