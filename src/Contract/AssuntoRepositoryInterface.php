<?php

namespace App\Contract;

use App\Entity\Assunto;

interface AssuntoRepositoryInterface
{
    /** @return Assunto[] */
    public function listar(): array;

    public function salvar(Assunto $assunto): void;
    public function excluir(Assunto $assunto): void;
}
