<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movie>
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function getTrendings(bool $daily = true, int $page = 1, int $itemsPerPage = 10): array
    {
        $trendingField = $daily ? 'trending_day_order' : 'trending_week_order';
        $qb = $this->createQueryBuilder('m');
        $qb->andWhere($qb->expr()->isNotNull('m.' . $trendingField))
            ->addOrderBy('m.' . $trendingField, 'ASC')
            ->setFirstResult(($page - 1) * $itemsPerPage)
            ->setMaxResults($itemsPerPage)
            ;

        return $qb->getQuery()->getResult();
    }
}
