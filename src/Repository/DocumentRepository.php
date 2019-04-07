<?php

namespace App\Repository;

use App\Entity\Document;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Document|null find($id, $lockMode = null, $lockVersion = null)
 * @method Document|null findOneBy(array $criteria, array $orderBy = null)
 * @method Document[]    findAll()
 * @method Document[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Document::class);
    }

    /**
     * Retourne  tous les documents en attente
     * @return Document[] returns all documents that are not taken
     */
    public function findAllDocumentsNotTaken(){
        return $this->createQueryBuilder('d')
            ->where('decision.isTaken = false')
            ->innerJoin('d.decision','decision')
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne les documents en attente pour le contributor $id
     * @param $id
     * @return Document[] array of Document objects
     */
    public function findAllContributorWaitingDocs($id){
        return $this->createQueryBuilder('d')
            ->where('decision.isTaken = false')
            ->andWhere("contributors.id= '$id'")
            ->innerJoin('d.contributors','contributors')
            ->innerJoin('d.decision','decision')
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Document[] Returns an array of Document objects
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
    public function findOneBySomeField($value): ?Document
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
