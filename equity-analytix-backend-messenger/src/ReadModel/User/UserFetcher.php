<?php

declare(strict_types=1);

namespace App\ReadModel\User;

use App\Entity\Chat\User\User;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

/**
 * Class UserFetcher
 *
 * @package   App\ReadModel\User
 * @author    Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 */
class UserFetcher
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
     * UserFetcher constructor.
     * @param Connection $connection
     * @param EntityManagerInterface $em
     */
    public function __construct(Connection $connection, EntityManagerInterface $em)
    {
        $this->connection = $connection;
        $this->repository = $em->getRepository(User::class);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function findUserById(int $id)
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'user_name',
                'role',
                'status',
                'created_at'
            )
            ->from('chat_user_users as u')
            ->andWhere('u.id = :id')
            ->setParameter(':id', $id)
            ->execute();
        return $stmt->fetch();
    }

    /**
     * @return mixed[]
     */
    public function findAllUsers()
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('chat_user_users')
            ->execute();

        return $stmt->fetchAll();
    }

    /**
     * @param int $userId
     *
     * @return mixed[]
     */
    public function getUserSettings(int $userId)
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('chat_setting_settings', 's')
            ->where('s.user_id = :userId')
            ->setParameter(':userId', $userId)
            ->execute();

        return $stmt->fetchAll();
    }
}
