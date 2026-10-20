<?php

namespace App\Repository;

use App\Contract\LivroRepositoryInterface;
use App\Entity\Livro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livro>
 */
class LivroRepository extends ServiceEntityRepository implements LivroRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livro::class);
    }

    public function listar(): array
    {
        return $this->createQueryBuilder('livro')
            ->leftJoin('livro.autores', 'autor')
            ->addSelect('autor')
            ->leftJoin('livro.assuntos', 'assunto')
            ->addSelect('assunto')
            ->orderBy('livro.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function salvar(Livro $livro): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($livro);
        $entityManager->flush();
    }

    public function excluir(Livro $livro): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->remove($livro);
        $entityManager->flush();
    }
}
