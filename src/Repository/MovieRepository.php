<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movie>
 *
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function save(Movie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Movie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

	/**
	 * Search one movie by this id in DQL
	 *
	 * @param int $id
	 * @return Movie|null
	 */
    public function findMovieDQL(int $id): ?Movie
	  {
        $query = $this->getEntityManager()->createQuery(('SELECT m FROM App\Entity\Movie m where m.id = ' . $id));
        $movies = $query->getResult();
        
        $query = $this->getEntityManager()->createQuery(
            'SELECT m, c, p, g
            FROM App\Entity\Movie m
            JOIN m.castings c
            JOIN c.person p
            JOIN m.genres g
            WHERE m.id = ' . $id .
            'ORDER BY c.creditOrder'
        );

        if (count($movies) === 1) {
            return $movies[0];
        } else {
            return null;
        }
	  }
	
	/**
	 * search a film by this id with Query Builder
	 *
	 * @param integer $id
	 * @return Movie|null
	 */
    public function findQB(int $id): ?Movie
    {
        $result = $this->createQueryBuilder('m')
            ->andWhere('m.id = :movie.id')
            ->setParameter('movie_id', $id)
            ->innerJoin('m.castings', 'c')
            ->addSelect('c')
            ->innerJoin('m.genres', 'g')
            ->addSelect('g')
            ->innerJoin('c.personn', 'p')
            ->addSelect('p')
            ->getQuery()
            ->getResult();
        
        if (count($result) === 1) {
        return $result[0];
        } else {
        return null;
        }
    }

    public function findAllOrderByDuration(): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.duration', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
                   
    }

//    /**
//     * @return Movie[] Returns an array of Movie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Movie
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
