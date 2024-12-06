<?php

namespace App\Repository;

use App\Entity\Quack;
use App\Model\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quack>
 */
class QuackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quack::class);
    }

    public function findAllQuacks() {
        return $this->createQueryBuilder('q')
            ->orderBy('q.created_at', 'DESC')
            ->where('q.active = 1')
            ->getQuery()
            ->getResult();
    }

    public function findAllCommentQuack(int $id)
    {
        return $this->createQueryBuilder('q')
            ->where('q.id = :id')
            ->setParameter('id', $id)
            ->leftJoin('q.author', 'a')
            ->addSelect('a')
            ->leftJoin('q.comments', 'c')
            ->addSelect('c')
            ->orderBy('q.created_at', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();

    }

    public function findBySearch(SearchData $searchData):array
    {
        $data = $this->createQueryBuilder('q')
            ->join('q.author', 'a')
            ->addSelect('a');

        if (!empty($searchData->query)){
            $data = $data

                ->where('a.duckname LIKE :query')
                ->setParameter('query', "%" .  $searchData->query ."%");
        }
        $data = $data
            ->getQuery()
            ->getResult();

        return $data;

    }



    //    /**
    //     * @return Quack[] Returns an array of Quack objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('q.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Quack
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
