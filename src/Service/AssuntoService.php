<?php

namespace App\Service;

use App\Contract\AssuntoRepositoryInterface;
use App\Entity\Assunto;

readonly class AssuntoService
{
    public function __construct(private AssuntoRepositoryInterface $assuntoRepository) {}

    public function listar(): array
    {
        return $this->assuntoRepository->listar();
    }

    public function cadastrar(Assunto $assunto): void
    {
        $this->assuntoRepository->salvar($assunto);
    }

    public function atualizar(Assunto $assunto): void
    {
        $this->assuntoRepository->salvar($assunto);
    }

    public function excluir(Assunto $assunto): void
    {
        $this->assuntoRepository->excluir($assunto);
    }
}
