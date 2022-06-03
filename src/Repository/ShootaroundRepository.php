<?php

namespace App\Repository;

use App\Entity\Shootarounds;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Shootarounds>
 *
 * @method Shootarounds|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shootarounds|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shootarounds[]    findAll()
 * @method Shootarounds[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShootaroundRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shootarounds::class);
    }

    public function add(Shootarounds $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Shootarounds $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Shootarounds[] Returns an array of Shootarounds objects
     */
    public function findByDate($dateStart, $dateEnd): array
    {
        return $this->createQueryBuilder('shoot')
            ->andWhere('shoot.date BETWEEN :dateStart AND :dateEnd ')
            ->setParameters(['dateStart'=>$dateStart, 'dateEnd'=>$dateEnd])
            ->orderBy('shoot.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?Shootarounds
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
