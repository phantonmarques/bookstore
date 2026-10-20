<?php

namespace App\Service;

use App\Contract\AutorRepositoryInterface;
use App\Entity\Autor;

readonly class AutorService
{
    public function __construct(private AutorRepositoryInterface $autorRepository) {}

    public function listar(): array
    {
        return $this->autorRepository->listar();
    }

    public function cadastrar(Autor $autor): void
    {
        $this->autorRepository->salvar($autor);
    }

    public function atualizar(Autor $autor): void
    {
        $this->autorRepository->salvar($autor);
    }

    public function excluir(Autor $autor): void
    {
        $this->autorRepository->excluir($autor);
    }
}
