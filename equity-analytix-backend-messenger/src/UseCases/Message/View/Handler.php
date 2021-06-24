<?php

declare(strict_types=1);

namespace App\UseCases\Message\View;

use App\Entity\Chat\Message\MessageRepository;
use App\Entity\Chat\Room\RoomRepository;
use App\Entity\Chat\User\UserRepository;
use App\Entity\Chat\ViewedMessage\ViewedMessage;
use App\UseCases\Flusher;
use Doctrine\ORM\EntityNotFoundException;
use DomainException;

/**
 * Class Handler
 *
 * @package App\UseCases\Message\View
 */
class Handler
{
    /**
     * @var MessageRepository
     */
    private $messages;
    /**
     * @var UserRepository
     */
    private $users;
    /**
     * @var RoomRepository
     */
    private $rooms;
    /**
     * @var Flusher
     */
    private $flusher;

    /**
     * Handler constructor.
     *
     * @param MessageRepository $messages
     * @param UserRepository    $users
     * @param RoomRepository    $rooms
     * @param Flusher           $flusher
     */
    public function __construct(
        MessageRepository $messages,
        UserRepository $users,
        RoomRepository $rooms,
        Flusher $flusher
    ) {
        $this->messages = $messages;
        $this->users    = $users;

        $this->rooms   = $rooms;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     *
     * @throws EntityNotFoundException
     */
    public function handle(Command $command): void
    {
        $user    = $this->users->get($command->userId);
        $message = $this->messages->get($command->id);
        $room    = $this->rooms->get($command->roomId);

        if (!$message->isForRoom($room)) {
            throw new DomainException('Message does not belong to this room.');
        }

        $viewedMessage = new ViewedMessage($user, $message);
        $user->addViewedMessage($viewedMessage);

        $this->flusher->flush();
    }
}
