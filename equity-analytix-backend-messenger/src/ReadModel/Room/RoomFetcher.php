<?php

declare(strict_types=1);

namespace App\ReadModel\Room;

use App\Entity\Chat\Room\Room;
use App\Entity\Chat\User\Role;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class RoomFetcher
 *
 * @author    Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 */
class RoomFetcher
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var \Doctrine\Persistence\ObjectRepository
     */
    private $repository;

    /**
     * RoomFetcher constructor.
     *
     * @param Connection             $connection
     * @param EntityManagerInterface $em
     */
    public function __construct(Connection $connection, EntityManagerInterface $em)
    {
        $this->connection = $connection;
        $this->repository = $em->getRepository(Room::class);
    }

    /**
     * @param int $userId
     *
     * @return string|null
     * @throws Exception
     */
    public function getUserRoom(int $userId): ?string
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select('ru.room_id')
            ->from('chat_user_users', 'u')
            ->leftJoin('u', 'chat_rooms_users', 'ru', 'ru.user_id = u.id')
            ->where('u.id = :userId')
            ->andWhere('u.role != :role')
            ->setParameter(':userId', $userId)
            ->setParameter(':role', Role::ADMIN)
            ->execute();

        return $stmt->fetch()['room_id'];
    }
}
