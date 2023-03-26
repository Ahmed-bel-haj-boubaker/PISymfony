<?php

namespace App\Repository;

use App\Entity\Hebergement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Hebergement>
 *
 * @method Hebergement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hebergement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hebergement[]    findAll()
 * @method Hebergement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HebergementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hebergement::class);
    }

    public function save(Hebergement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Hebergement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findHebergementByemail(){
        return $this->createQueryBuilder('q')
        
        ->orderBy('q.id','ASC')
        ->getQuery()
        ->getResult();
    }
    #DQL
    public function findHebergementByemailDQL(){
        $entityManager=$this->getEntityManager();
        $query=$entityManager->createQuery('SELECT p from App\Entity\Hebergement p ORDER BY p.id ASC');
        return $query->getResult();
    }
    #filtrer les etudiants par NSC
#QueryBuilder
public function findHebergementByNSC($NSC){
    return $this->createQueryBuilder('h')
        ->join('h.localisation', 'l')
        //->join('h.nomHeberg', 'n')
        ->where('h.nomHeberg LIKE :NSC')

        ->orWhere('l.lieux LIKE :NSC')
      
        ->setParameter('NSC', '%' . $NSC . '%')
        ->getQuery()
        ->getResult();
}
#DQL
    public function findHebergementByNSCDQL($NSC){
        $entityManager=$this->getEntityManager();
        $query=$entityManager->createQuery('
    SELECT p FROM App\Entity\Hebergement p
    WHERE p.localisation = :NSC OR  p.nomHeberg = :NSC'

)
->setParameter('NSC', '%' . $NSC . '%');

return $query->getResult();

    }


//    /**
//     * @return Hebergement[] Returns an array of Hebergement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Hebergement
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
