<?php

namespace App\Repository;

use App\Contract\AutorRepositoryInterface;
use App\Entity\Autor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Autor>
 */
class AutorRepository extends ServiceEntityRepository implements AutorRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Autor::class);
    }

    public function listar(): array
    {
        return $this->findBy([], ['id' => 'ASC']);
    }

    public function salvar(Autor $autor): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($autor);
        $entityManager->flush();
    }

    public function excluir(Autor $autor): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->remove($autor);
        $entityManager->flush();
    }
}
