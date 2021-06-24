<?php
declare(strict_types=1);

namespace App\Entity\Chat\Message;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Class MessageRepository
 */
class MessageRepository
{
    private $repo;

    /**
     * MessageRepository constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(Message::class);
    }

    /**
     * @param $id
     * @return Message
     * @throws EntityNotFoundException
     */
    public function get($id): Message
    {
        /** @var Message $message */
        if (!$message = $this->repo->find($id)) {
            throw new EntityNotFoundException('Message not found');
        }

        return $message;
    }
}
