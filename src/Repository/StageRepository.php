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


    public function findStagesParEntreprise($nomEntreprise)
    {

        return $this->createQueryBuilder('s')
                    ->join('s.entreprise','e')
                    ->andWhere('e.nom = :nomEntreprise')
                    ->setParameter('nomEntreprise', $nomEntreprise)
                    ->getQuery()
                    ->getResult();
    }

    public function findStagesParFormation($nomFormation)
    {
        $gestionnaireEntite = $this->getEntityManager();

        $requete = $gestionnaireEntite->createQuery('SELECT s,tf,e
                                                     FROM App\Entity\Stage s
                                                     JOIN s.typeFormation tf
                                                     JOIN s.entreprise e
                                                     WHERE tf.nomCourt = :formation');

        $requete->setParameter('formation',$nomFormation);

        return $requete->execute();
    }


      
    public function recupererToutLesStages()
    {
        return $this->createQueryBuilder('s')
                    ->select('s, e')
                    ->join('s.entreprise', 'e')
                    ->getQuery()
                    ->getResult();
    }

    public function recupererInformationsStage($idStage)
    {
        return $this->createQueryBuilder('s')
                    ->select('s , e , tf')
                    ->join('s.entreprise', 'e')
                    ->join('s.typeFormation', 'tf')
                    ->andWhere('s.id = :idStage')
                    ->setParameter('idStage', $idStage)
                    ->getQuery()
                    ->getResult();
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
