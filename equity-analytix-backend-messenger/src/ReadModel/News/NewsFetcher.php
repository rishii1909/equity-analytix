<?php

namespace App\ReadModel\News;

use App\Entity\Chat\ArchivedNews\ArchiveNewsRepository;
use App\Entity\Chat\News\News;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class NewsFetcher
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var ObjectRepository
     */
    private $repository;
    /**
     * @var ArchiveNewsRepository
     */
    private $archiveNewsRepository;

    /**
     * UserFetcher constructor.
     *
     * @param Connection             $connection
     * @param EntityManagerInterface $em
     * @param ArchiveNewsRepository  $archiveNewsRepository
     */
    public function __construct(Connection $connection, EntityManagerInterface $em, ArchiveNewsRepository $archiveNewsRepository)
    {
        $this->connection            = $connection;
        $this->repository            = $em->getRepository(News::class);
        $this->archiveNewsRepository = $archiveNewsRepository;
    }

    /**
     * @param int               $userId
     * @param DateTimeImmutable $date
     *
     * @return array
     * @throws Exception
     */
    public function findActiveUserNewsForCurrentDay(int $userId, DateTimeImmutable $date): array
    {
        $qb = $this->connection->createQueryBuilder();

        $stmt = $qb->select('n.id')
            ->from('chat_news_news', 'n')
            ->leftJoin('n', 'chat_archived_news', 'a', 'a.news_unit_id = n.id AND a.user_id = :userId')
            ->where('n.created_at >= :start')
            ->andWhere('n.created_at <= :end')
            ->andWhere('a.news_unit_id is NULL')
            ->setParameter(':userId', $userId)
            ->setParameter(':start', $date->format('Y-m-d 00:00:00'))
            ->setParameter(':end', $date->format('Y-m-d 23:59:59'))
            ->groupBy('n.id')
            ->orderBy('n.created_at')
            ->execute();

        return $stmt->fetchAll();
    }

    /**
     * @param string $query
     *
     * @return mixed
     */
    public function searchNews(string $query)
    {
        $qb     = $this->connection->createQueryBuilder();
        $result = $qb->select(
            'm.id',
            'u.user_name as user',
            'm.text',
            'm.status',
            'm.created_at'
        )
            ->from('chat_news_news', 'm')
            ->join('m', 'chat_user_users', 'u', 'm.user_id = u.id')
            ->where(
                $qb->expr()->like('m.text', ':query')
            )
            ->setParameter(':query', '%' . $query . '%')
            ->execute();

        return $result->fetchAll();
    }

    /**
     * @param int               $userId
     * @param DateTimeImmutable $date
     *
     * @return array
     * @throws Exception
     */
    public function findArchivedUsersNewsForCurrentDay(int $userId, DateTimeImmutable $date): array
    {
        $qb = $this->connection->createQueryBuilder();

        $stmt = $qb->select('n.id')
            ->from('chat_news_news', 'n')
            ->leftJoin('n', 'chat_archived_news', 'a', 'a.news_unit_id = n.id AND a.user_id = :userId')
            ->where('n.created_at >= :start')
            ->andWhere('n.created_at <= :end')
            ->andWhere('a.news_unit_id is not NULL')
            ->setParameter(':start', $date->format('Y-m-d 00:00:00'))
            ->setParameter(':end', $date->format('Y-m-d 23:59:59'))
            ->setParameter(':userId', $userId)
            ->groupBy('n.id')
            ->orderBy('n.created_at')
            ->execute();

        return $stmt->fetchAll();
    }

    /**
     * @param string $cutoffDate
     * @param int    $userId
     *
     * @return array
     * @throws Exception
     */
    public function findLastViewedNews(string $cutoffDate, int $userId): array
    {
        $qb = $this->connection->createQueryBuilder();

        $stmt = $qb->select(
            'n.id',
            'u.id as user',
            'n.text',
            'n.status',
            'n.created_at'
        )
            ->from('chat_news_news', 'n')
            ->innerJoin('n', 'chat_user_users', 'u', 'u.id = n.user_id')
            ->leftJoin('n', 'chat_viewed_news', 'v', 'v.news_unit_id = n.id AND v.user_id = :userId')
            ->leftJoin('n', 'chat_archived_news', 'a', 'a.news_unit_id = n.id AND a.user_id = :userId')
            ->where('DATE(n.created_at) = :time')
            ->andWhere('v.news_unit_id is not NULL')
            ->andWhere('a.news_unit_id is NULL')
            ->setParameter(':time', $cutoffDate)
            ->setParameter(':userId', $userId)
            ->groupBy('n.id')
            ->orderBy('n.created_at')
            ->execute();

        $news = $stmt->fetchAll();

        $files = $this->batchLoadFiles(array_column($news, 'id'));

        return array_map(function (array $item) use ($files) {
            return array_merge($item, [
                'attachments' => array_filter($files, function (array $file) use ($item) {
                    return $file['entity_id'] === $item['id'];
                })
            ]);
        }, $news);
    }

    /**
     * @param string $cutoffDate
     * @param int    $userId
     *
     * @return array
     * @throws Exception
     */
    public function findLastNotViewedNews(string $cutoffDate, int $userId): array
    {
        $qb = $this->connection->createQueryBuilder();

        $stmt = $qb->select(
            'n.id',
            'u.id as user',
            'n.text',
            'n.status',
            'n.created_at'
        )
            ->from('chat_news_news', 'n')
            ->innerJoin('n', 'chat_user_users', 'u', 'u.id = n.user_id')
            ->leftJoin('n', 'chat_viewed_news', 'v', 'v.news_unit_id = n.id AND v.user_id = :userId')
            ->leftJoin('n', 'chat_archived_news', 'a', 'a.news_unit_id = n.id AND a.user_id = :userId')
            ->where('DATE(n.created_at) = :time')
            ->andWhere('v.news_unit_id is NULL')
            ->andWhere('a.news_unit_id is NULL')
            ->setParameter(':time', $cutoffDate)
            ->setParameter(':userId', $userId)
            ->groupBy('n.id')
            ->orderBy('n.created_at')
            ->execute();

        $news = $stmt->fetchAll();

        $files = $this->batchLoadFiles(array_column($news, 'id'));

        return array_map(function (array $item) use ($files) {
            return array_merge($item, [
                'attachments' => array_filter($files, function (array $file) use ($item) {
                    return $file['entity_id'] === $item['id'];
                })
            ]);
        }, $news);
    }

    /**
     * @param int         $offset
     * @param int         $limit
     * @param string|null $direction
     *
     * @return array[]|null
     * @throws Exception
     */
    public function findAll(int $offset, int $limit, ?string $direction): ?array
    {
        $firstResult = ($offset - 1) * $limit;
        $direction   = $direction === 'desc' ? 'desc': 'asc';

        $qb = $this->connection->createQueryBuilder()
            ->select(
                'n.id',
                'u.id as user',
                'n.text',
                'n.status',
                'n.created_at'
            )
            ->from('chat_news_news', 'n')
            ->join('n', 'chat_user_users', 'u', 'u.id = n.user_id')
            ->setFirstResult($firstResult)
            ->setMaxResults($limit)
            ->groupBy('n.id')
            ->orderBy('n.created_at', $direction)
            ->execute();

        $news = $qb->fetchAll();

        $files = $this->batchLoadFiles(array_column($news, 'id'));

        return array_map(function (array $item) use ($files) {
            return array_merge($item, [
                'attachments' => array_filter($files, function (array $file) use ($item) {
                    return $file['entity_id'] === $item['id'];
                })
            ]);
        }, $news);
    }

    /**
     * @param array $ids
     *
     * @return array
     * @throws Exception
     */
    private function batchLoadFiles(array $ids): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'f.entity_id',
                'TRIM(CONCAT(f.info_path, \'/\', f.info_name)) AS url'
            )
            ->from('file_files', 'f')
            ->where('f.entity_type = :type')
            ->andWhere('f.entity_id IN (:ids)')
            ->setParameter(':type', News::class)
            ->setParameter(':ids', $ids, Connection::PARAM_INT_ARRAY)
            ->orderBy('f.date')
            ->execute();

        return $stmt->fetchAll();
    }
}
