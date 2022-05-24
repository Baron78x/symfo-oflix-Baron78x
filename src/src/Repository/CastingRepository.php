<?php

namespace App\Repository;

use App\Entity\Movie;
use App\Entity\Casting;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Casting>
 *
 * @method Casting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Casting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Casting[]    findAll()
 * @method Casting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CastingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Casting::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Casting $entity, bool $flush = true): void
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
    public function remove(Casting $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * Castings for the movie page (joined to Person)
     * 
     * @param Movie $movie Movie to get casting from
     */
    public function findAllByMovieJoinedToPerson(Movie $movie)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT c, p -- Ramène-moi les objets des deux tables
            FROM App\Entity\Casting c -- Depuis l\'entité Casting
            INNER JOIN c.person p -- Fais la jointure sur la personne liée au casting
            WHERE c.movie = :movie -- Où le film du casting est le film fourni
            ORDER BY c.creditOrder ASC'
        )->setParameter('movie', $movie); // Paramètre de la requête préparée

        return $query->getResult();
    }

    /**
     * Castings for the movie page (joined to Person)
     * 
     * <3 Bruno
     */
    public function findCastingOfMovieQB($movie)
    {
        // Va me chercher les castings
        return $this->createQueryBuilder('casting')
            // Dont le film est fourni
            ->where('casting.movie = :movie')
            // Fais une jointure sur l'entité Person
            ->innerJoin('casting.person', 'person')
            // Ajoute les objets de type Person aux résultats
            ->addSelect('person')
            // (pour le film fourni)
            ->setParameter('movie', $movie)
            ->orderBy('casting.creditOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Casting[] Returns an array of Casting objects
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
    public function findOneBySomeField($value): ?Casting
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
