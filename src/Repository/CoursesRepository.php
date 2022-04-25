<?php

namespace App\Repository;

use App\Entity\Courses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Courses>
 *
 * @method Courses|null find($id, $lockMode = null, $lockVersion = null)
 * @method Courses|null findOneBy(array $criteria, array $orderBy = null)
 * @method Courses[]    findAll()
 * @method Courses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoursesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Courses::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Courses $entity, bool $flush = true): void
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
    public function remove(Courses $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
    /**
      * @return Courses[] Returns an array of Courses objects
     */
    
    public function findByDate($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.date LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findByCategory($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.category LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findBytrainer($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.trainer LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findByAddress($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.date LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult()
        ;
    }

    public function SortByDate()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.Date', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function SortByCategory()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.Category', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function SortByAddress()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.address', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function SortBytrainer()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.trainer', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

     /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function removeExpired(): void
    {
         $this->createQueryBuilder('c')
        ->delete()
        ->where("c.date < :da")
        ->setParameter('da',date("Y-m-d H:i:s"))
        ->getQuery()
        ->execute();
    }

    // /**
    //  * @return Courses[] Returns an array of Courses objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Courses
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
