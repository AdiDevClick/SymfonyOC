<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Users>
 *
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    /**
     * @return Users[] Returns an array of Users objects
     */
    public function findUsersByAgeInterval(): array
    {
        $qb = $this->createQueryBuilder('u');
        return $qb->getQuery()
            ->getResult();
    }

    public function statsUsersByAgeInterval(int $ageMin, int $ageMax): array
    {
        $qb = $this->createQueryBuilder('u')
            ->select('avg(u.age) as ageAvg, count(u.id) as nbUsers');
        $this->addAgeInterval($qb, $ageMin, $ageMax);
        return $qb->getQuery()
            ->getScalarResult();
    }
    public function addAgeInterval(QueryBuilder $qb, int $ageMin, int $ageMax)
    {
            $qb->andWhere('u.age >= :ageMin AND u.age <= :ageMax')
            // ->setParameter('ageMin', $ageMin)
            // ->setParameter('ageMax', $ageMax)
                ->setParameters([
                    'ageMax' => $ageMax,
                    'ageMin' => $ageMin,
                ])
                ->orderBy('u.name', 'ASC')
                ->setMaxResults(50);
    }

//    public function findOneBySomeField($value): ?Users
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

// public function findUsersByAgeInterval(int $ageMin, int $ageMax): array
// {
//     return $this->createQueryBuilder('u')
//         ->andWhere('u.age >= :ageMin AND u.age <= :ageMax')
//         // ->setParameter('ageMin', $ageMin)
//         // ->setParameter('ageMax', $ageMax)
//         ->setParameters([
//             'ageMax' => $ageMax,
//             'ageMin' => $ageMin,
//             ])
//         ->orderBy('u.name', 'ASC')
//         ->setMaxResults(50)
//         ->getQuery()
//         ->getResult()
//     ;
// }
}
