<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Room|null find($idr, $lockMode = null, $lockVersion = null)
 * @method Room|null findOneBy(array $criteria, array $orderBy = null)
 * @method Room[]    findAll()
 * @method Room[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Room $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Room $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

     /**
      * @return Room[] Returns an array of Room objects
      */

    public function findallrooms()
    {
        return $this->createQueryBuilder('r')
            //->andWhere('r.exampleField = :val')
            //->setParameter('val', $value)
           // ->orderBy('r.idr', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByName($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.roomname LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findByNumber($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.roomnumber LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findByCapacity($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.max_nbr LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult()
            ;
    }

    public function SortByName()
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.roomname', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function SortByNumber()
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.roomnumber', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
    /*
    public function findOneBySomeField($value): ?Room
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
