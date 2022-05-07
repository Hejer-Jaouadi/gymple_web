<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($idg, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(User $entity, bool $flush = true): void
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
    public function remove(User $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.idg', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findByEmail(String $value, String $v)
    {
        return $this->createQueryBuilder('u')
            ->where('u.email = :val')
            ->andWhere('u.password = :vall')
            ->setParameter('val', $value)
            ->setParameter('vall', $v)
            ->setMaxResults(1)
            ->getQuery() 
            ->getResult()
        ;
    }

    public function findByEmailA(String $value)
    {
        return $this->createQueryBuilder('u')
            ->where('u.email = :val')
            ->setParameter('val', $value)
            ->setMaxResults(1)
            ->getQuery() 
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?USer
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function SortByEmail()
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->orderBy('c.email', 'ASC')
            ->getQuery() 
            ->getResult()
           ;
    }

    public function SortByRole()
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->orderBy('c.role', 'ASC')
            ->getQuery()
            ->getResult() 
            ;
    }

    public function lastfind($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.lastName LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult()
            ;
    }

    public function emailfind($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.email LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getAll()
    {
        $queryBuilder = $this->createQueryBuilder('c')
            ->select('c')
            ->orderBy('c.id');
            
        $limit = 1000;
        $offset = 0;
            
        while (true) {
            $queryBuilder->setFirstResult($offset);
            $queryBuilder->setMaxResults($limit);
            
            $customers = $queryBuilder->getQuery()->getResult();
            
            if (count($customers) === 0) {
                break;
            }
            
            foreach ($customers as $customer) {
                yield $customer;
                $this->_em->detach($customer);
            }
            
            $offset += $limit;
        }
    }
}