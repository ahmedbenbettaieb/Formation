<?php

namespace App\Repository;

use App\Entity\Questiontest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Questiontest|null find($id, $lockMode = null, $lockVersion = null)
 * @method Questiontest|null findOneBy(array $criteria, array $orderBy = null)
 * @method Questiontest[]    findAll()
 * @method Questiontest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestiontestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Questiontest::class);
    }

    public function searchQuestion(string $search,int $idt): array
    {
        $entityManager = $this->getEntityManager() ;
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('q')
            ->from(Questiontest::class, 'q')
            ->where('q.idTest = :idt')
            ->andWhere('q.designation like :search or q.note like :search or q.reponseCorrecte like :search
             or q.reponseFausse1 like :search or q.reponseFausse2 like :search or q.reponseFausse3 like :search')
            ->setParameter('idt', $idt)
            ->setParameter('search','%'.$search.'%') ;

        $query = $queryBuilder->getQuery();

        return $query->execute();
    }

    // /**
    //  * @return Questiontest[] Returns an array of Questiontest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Questiontest
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
