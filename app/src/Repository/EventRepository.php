<?php
/**
 * Event repository.
 */

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class EventRepository.
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @extends ServiceEntityRepository<Event>
 */
class EventRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in configuration files.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * Query all records.
     *
     * @param array<string, object> $filters Filters
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(array $filters): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->select(
                'partial event.{id, startDate, endDate, title}',
                'partial category.{id, title}'
            )
            ->join('event.category', 'category')
            ->orderBy('event.startDate', 'DESC');

        return $this->applyFiltersToList($queryBuilder, $filters);
    }

    /**
     * Count events by category.
     *
     * @param Category $category Category
     *
     * @return int Number of events in category
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countByCategory(Category $category): int
    {
        $qb = $this->getOrCreateQueryBuilder();

        return $qb->select($qb->expr()->countDistinct('event.id'))
            ->where('event.category = :category')
            ->setParameter(':category', $category)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Save entity.
     *
     * @param Event $event Event entity
     */
    public function save(Event $event): void
    {
        $this->_em->persist($event);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Event $event Event entity
     */
    public function delete(Event $event): void
    {
        $this->_em->remove($event);
        $this->_em->flush();
    }

    /**
     * Find ongoing events.
     *
     * @param \DateTimeInterface $now Now
     *
     * @return Event[] Returns an array of Event objects
     */
    public function findOngoingEvents(\DateTimeInterface $now): array
    {
        return $this->createQueryBuilder('event')
            ->Where('event.startDate <= :now')
            ->andWhere('event.endDate >= :now')
            ->setParameter('now', $now)
            ->orderBy('event.startDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find future events.
     *
     * @param \DateTimeInterface $now Now
     *
     * @return Event[] Returns an array of Event objects
     */
    public function findFutureEvents(\DateTimeInterface $now): array
    {
        return $this->createQueryBuilder('event')
            ->where('event.startDate > :now')
            ->setParameter('now', $now)
            ->orderBy('event.startDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('event');
    }

    /**
     * Apply filters to paginated list.
     *
     * @param QueryBuilder          $queryBuilder Query builder
     * @param array<string, object> $filters      Filters array
     *
     * @return QueryBuilder Query builder
     */
    private function applyFiltersToList(QueryBuilder $queryBuilder, array $filters = []): QueryBuilder
    {
        if (isset($filters['category']) && $filters['category'] instanceof Category) {
            $queryBuilder->andWhere('category = :category')
                ->setParameter('category', $filters['category']);
        }

        return $queryBuilder;
    }
}
