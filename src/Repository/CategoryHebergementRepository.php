<?php

namespace App\Repository;

use App\Entity\CategoryHebergement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoryHebergement>
 *
 * @method CategoryHebergement|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryHebergement|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryHebergement[]    findAll()
 * @method CategoryHebergement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryHebergementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryHebergement::class);
    }

    public function save(CategoryHebergement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CategoryHebergement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CategoryHebergement[] Returns an array of CategoryHebergement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CategoryHebergement
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
