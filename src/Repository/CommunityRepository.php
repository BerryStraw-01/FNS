<?php

namespace App\Repository;

use App\Entity\Community;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Communitiy>
 *
 * @method Community|null find($id, $lockMode = null, $lockVersion = null)
 * @method Community|null findOneBy(array $criteria, array $orderBy = null)
 * @method Community[]    findAll()
 * @method Community[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommunityRepository extends ServiceEntityRepository {
  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Community::class);
  }

  public function save(Community $entity, bool $flush = true): void {
    $entity->setUpdateAt(new \DateTimeImmutable());

    $this->getEntityManager()->persist($entity);

    if ($flush) {
      $this->getEntityManager()->flush();
    }
  }

  public function remove(Community $entity, bool $flush = true): void {
    $this->getEntityManager()->remove($entity);

    if ($flush) {
      $this->getEntityManager()->flush();
    }
  }

  public function findByUpdated(int $limit, int $offset = 0) {
    return $this->createQueryBuilder("c")
      ->orderBy("c.updateAt", "ASC")
      ->setFirstResult($offset)
      ->setMaxResults($limit)
      ->getQuery()
      ->getResult()
    ;
  }

//    /**
//     * @return Community[] Returns an array of Community objects
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

//    public function findOneBySomeField($value): ?Communitiy
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
