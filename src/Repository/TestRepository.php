<?php

namespace App\Repository;

use App\Entity\Test;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Test|null find($id, $lockMode = null, $lockVersion = null)
 * @method Test|null findOneBy(array $criteria, array $orderBy = null)
 * @method Test[]    findAll()
 * @method Test[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Test::class);
    }

    public function rechercherTest(string $search,int $idf): array
    {

        $qb = $this->createQueryBuilder('t')
            ->where('t.idFormateur = :idf')
            ->andWhere('t.sujet like :search or t.duree like :search or t.nbEtudiantPasses like :search or t.nbEtudiantsAdmis like :search')
            ->setParameter('idf', $idf)
            ->setParameter('search','%'.$search.'%') ;


        $query = $qb->getQuery();

        return $query->execute();
    }

    public function rechercherTestFront(string $search): array
    {

        $qb = $this->createQueryBuilder('t')
            ->andWhere('t.sujet like :search or t.duree like :search ')
            ->setParameter('search','%'.$search.'%') ;


        $query = $qb->getQuery();

        return $query->execute();
    }

    // /**
    //  * @return Test[] Returns an array of Test objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Test
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
