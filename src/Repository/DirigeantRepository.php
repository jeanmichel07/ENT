<?php

namespace App\Repository;

use App\Entity\Dirigeant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Dirigeant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dirigeant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dirigeant[]    findAll()
 * @method Dirigeant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DirigeantRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Dirigeant::class);
    }
    public function findDiri($username,$password)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.username = :username')
            ->andWhere('d.password = :password')
            ->setParameter('username', $username)
            ->setParameter('password', sha1($password))
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Dirigeant[] Returns an array of Dirigeant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Dirigeant
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
