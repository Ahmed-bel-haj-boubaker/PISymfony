<?php

namespace App\Repository;

use App\Entity\MatchF;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Matchf>
 *
 * @method Matchf|null find($id, $lockMode = null, $lockVersion = null)
 * @method Matchf|null findOneBy(array $criteria, array $orderBy = null)
 * @method Matchf[]    findAll()
 * @method Matchf[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchFRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MatchF::class);
    }

    public function add(MatchF $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MatchF $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findFinishedMatches()
    {
        return $this->createQueryBuilder('m')
                    ->where('m.heurefinM < :current_time')
                    ->andWhere('m.resultatA IS NOT NULL')
                    ->andWhere('m.resultatB IS NOT NULL')
                    ->setParameter('current_time', new \DateTime())
                    ->setMaxResults(1)
                    ->getQuery()
                    ->getResult();
    }

    public function findUpcomingMatches()
    {
        $qb = $this->createQueryBuilder('m');
        $qb->where('m.dateMatch >= :currentDate')
        ->andWhere('m.heurefinM >= :currentTime')
        ->setMaxResults(3)
        ->setParameter('currentDate', new \DateTime())
        ->setParameter('currentTime', new \DateTime());
        

        return $qb->getQuery()->getResult();
    }

    public function findByFilters($price)
    {
        return $this->createQueryBuilder('m')
                    ->where('m.prix = :prix')
                    ->setParameter('prix', $price)
                    ->getQuery()
                    ->getResult();
    }

    public function findMatches($limit = 10)
    {
        return $this->createQueryBuilder('m')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    

//    /**
//     * @return MatchF[] Returns an array of MatchF objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MatchF
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
