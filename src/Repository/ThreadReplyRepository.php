<?php

namespace App\Repository;

use App\Entity\Thread;
use App\Entity\ThreadReply;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ThreadReply>
 *
 * @method ThreadReply|null find($id, $lockMode = null, $lockVersion = null)
 * @method ThreadReply|null findOneBy(array $criteria, array $orderBy = null)
 * @method ThreadReply[]    findAll()
 * @method ThreadReply[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThreadReplyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ThreadReply::class);
    }

    public function save(ThreadReply $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ThreadReply $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Tri les messages d'un thread par date et passe les messages des vétérinaires en premier.
     *
     * @param Thread $thread Le thread
     *
     * @return ThreadReply[] Les messages du thread
     */
    public function findSortByVeto(Thread $thread): array
    {
        $messages = $this->findBy(['thread' => $thread], ['updatedAt' => 'DESC']);

        foreach ($messages as $key => $element) {
            if ($element->getUser()->isVeto()) {
                array_splice($messages, $key, 1); // Remove element from array
                array_unshift($messages, $element); // Add element to beginning of array
            }
        }

        /*usort($messages, function (ThreadReply $first) {
            return !$first->getUser()->isVeto();
        });*/

        return $messages;
    }

//    /**
//     * @return ThreadReply[] Returns an array of ThreadReply objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ThreadReply
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
