<?php

namespace App\Repository;

use App\Entity\Questionquiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Questionquiz|null find($id, $lockMode = null, $lockVersion = null)
 * @method Questionquiz|null findOneBy(array $criteria, array $orderBy = null)
 * @method Questionquiz[]    findAll()
 * @method Questionquiz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionquizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Questionquiz::class);
    }

    public function searchQuestion(string $search,int $idt): array
    {
        $entityManager = $this->getEntityManager() ;
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('q')
            ->from(Questionquiz::class, 'q')
            ->where('q.idQuiz = :idt')
            ->andWhere('q.designation like :search or q.note like :search or q.reponseCorrecte like :search
             or q.reponseFausse1 like :search or q.reponseFausse2 like :search or q.reponseFausse3 like :search')
            ->setParameter('idt', $idt)
            ->setParameter('search','%'.$search.'%') ;

        $query = $queryBuilder->getQuery();

        return $query->execute();
    }

    // /**
    //  * @return Questionquiz[] Returns an array of Questionquiz objects
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
    public function findOneBySomeField($value): ?Questionquiz
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
