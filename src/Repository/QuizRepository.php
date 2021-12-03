<?php

namespace App\Repository;

use App\Entity\Quiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Quiz|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quiz|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quiz[]    findAll()
 * @method Quiz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quiz::class);
    }

    public function rechercherQuiz(string $search,int $idf): array
    {

        $qb = $this->createQueryBuilder('q')
            ->where('q.idFormateur = :idf')
            ->andWhere('q.sujet like :search')
            ->setParameter('idf', $idf)
            ->setParameter('search','%'.$search.'%') ;


        $query = $qb->getQuery();

        return $query->execute();
    }

    public function rechercherQuizFront(string $search): array
    {
        $qb = $this->createQueryBuilder('q')
            ->andWhere('q.sujet like :search')
            ->setParameter('search','%'.$search.'%') ;

        $query = $qb->getQuery();

        return $query->execute();
    }

    // /**
    //  * @return Quiz[] Returns an array of Quiz objects
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
    public function findOneBySomeField($value): ?Quiz
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
