<?php

namespace App\Repository;

use App\Entity\Stage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Stage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stage[]    findAll()
 * @method Stage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stage::class);
    }

    //Retourne les stages proposés par l'entreprise dont le nom est fournis en paramètre
    public function findByEntreprise($nomEntreprise)
    {
      return $this->createQueryBuilder('stages')
                 ->join('stages.entreprise','entreprise')
                 ->where('entreprise.nom = :nomEntreprise')
                 ->setParameter('nomEntreprise',$nomEntreprise)
                 ->getQuery()
                 ->getResult();
    }

    //Retourne les stages proposés dans la formation dont le nom est fournis en paramètre
    public function findByFormation($nomFormation)
    {
      return $this->getEntityManager()
                  ->createQuery(
                    'SELECT stages
                    FROM App\Entity\Stage stages
                    JOIN stages.formations formations
                    WHERE formations.nom = :nomFormation')
                  ->setParameter('nomFormation',$nomFormation)
                  ->execute();
    }

    // /**
    //  * @return Stage[] Returns an array of Stage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Stage
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
