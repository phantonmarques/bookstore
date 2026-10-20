<?php

namespace App\Repository;

use App\Contract\AssuntoRepositoryInterface;
use App\Entity\Assunto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Assunto>
 */
class AssuntoRepository extends ServiceEntityRepository implements AssuntoRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Assunto::class);
    }

    public function listar(): array
    {
        return $this->findBy([], ['id' => 'ASC']);
    }

    public function salvar(Assunto $assunto): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($assunto);
        $entityManager->flush();
    }

    public function excluir(Assunto $assunto): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->remove($assunto);
        $entityManager->flush();
    }
}
