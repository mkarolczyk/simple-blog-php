<?php

declare(strict_types=1);

namespace App\Blog\Infrastructure\Doctrine\Repository;

use App\Blog\Application\Entity\BlogPost;
use App\Blog\Application\Query\BlogPostQueryRepositoryInterface;
use App\Blog\Application\Query\BlogPostView;
use App\Blog\Application\Repository\BlogPostRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

final class DoctrineBlogPostRepository extends ServiceEntityRepository implements BlogPostRepositoryInterface, BlogPostQueryRepositoryInterface
{
    public const TABLE_NAME = 'blogPost';

    private Connection $connection;

    public function __construct(ManagerRegistry $registry, Connection $connection)
    {
        $this->connection = $connection;

        parent::__construct($registry, BlogPost::class);
    }

    public function add(BlogPost $blogPost): void
    {
        $this->_em->persist($blogPost);
        $this->_em->flush();
    }

    public function getById(string $blogPostId, ?int $version = null): BlogPost
    {
        $blogPost = $this->find($blogPostId, ($version > 0 ? LockMode::OPTIMISTIC : null), $version);

        if (!$blogPost instanceof BlogPost) {
            throw new NoResultException();
        }

        return $blogPost;
    }

    public function findAll(int $page = 1, int $maxItems = 30): ?array
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->select('*')
            ->from(self::TABLE_NAME, 'bp')
            ->setFirstResult($maxItems * ($page - 1))
            ->setMaxResults($maxItems);

        $data = $this->connection->fetchAllAssociative($queryBuilder->getSQL());

        $array = [];
        foreach ($data as $item) {
            $array[] = BlogPostView::deserialize($item);
        }

        return $array;
    }

    public function findById(string $blogPostId): ?BlogPostView
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(self::TABLE_NAME, 'bp')
            ->where('bp.postId = :blogPostId')
            ->setParameter('blogPostId', $blogPostId);

        $data = $this->connection->fetchAssociative($queryBuilder->getSQL(), $queryBuilder->getParameters());

        if ($data === false) {
            return null;
        }

        return BlogPostView::deserialize($data);
    }

    public function count(array $criteria = []): int
    {
        return parent::count($criteria);
    }
}
