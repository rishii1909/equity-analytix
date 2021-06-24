<?php

declare(strict_types=1);

namespace App\ReadModel\Message;

use App\Entity\Chat\Message\Message;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

/**
 * Class MessageFetcher
 *
 * @package   App\ReadModel\Message
 * @author    Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 */
class MessageFetcher
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
     *
     * @param Connection $connection
     * @param EntityManagerInterface $em
     */
    public function __construct(Connection $connection, EntityManagerInterface $em)
    {
        $this->connection = $connection;
        $this->repository = $em->getRepository(Message::class);
    }

    /**
     * @param int $roomId
     *
     * @return mixed[]
     * @throws Exception
     */
    public function getRoomMessages(int $roomId)
    {

        $stmt = $this->connection->createQueryBuilder()
            ->select('m.id',
                'm.user_id',
                'u.user_name as user_name',
                'm.chat_room_id',
                'm.text',
                'm.created_at')
            ->from('chat_message_messages', 'm')
            ->leftJoin('m', 'chat_user_users', 'u', 'm.user_id = u.id')
            ->where('m.chat_room_id = :roomId')
            ->setParameter(':roomId', $roomId)
            ->execute()
        ;

            return $stmt->fetchAll();
    }

    /**
     * @param int $userId
     * @param int $roomId
     *
     * @return int
     * @throws Exception
     */
    public function getNotViewedCount(int $userId, int $roomId): int
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select('COUNT(m.id) as count')
            ->from('chat_message_messages', 'm')
            ->leftJoin(
                'm',
                'chat_viewed_messages',
                'vm',
                'vm.message_unit_id = m.id AND vm.user_id = :userId'
            )
            ->where('vm.message_unit_id is NULL')
            ->andWhere('m.chat_room_id = :roomId')
            ->setParameter(':userId', $userId)
            ->setParameter(':roomId', $roomId)
            ->execute();

        return (int)$stmt->fetch()['count'];
    }
}
