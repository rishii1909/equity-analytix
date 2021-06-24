<?php
declare(strict_types=1);

namespace App\UseCases\Message\Create\Handler;

use App\Entity\Chat\Message\Id;
use App\Entity\Chat\Message\Message;
use App\Entity\Chat\User\UserRepository;
use App\Repository\RoomRepository;
use App\UseCases\Message\Create\AbstractMessageCommand;
use App\UseCases\Message\Create\AbstractPrivateMessageCommand;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class MessageHandler
 */
class PrivateMessageHandler implements MessageHandlerInterface
{
    /**@var UserRepository*/
    private $userRepository;
    /**@var RoomRepository*/
    private $roomRepository;
	/**@var EntityManagerInterface*/
	private $em;

	/**
     * @param UserRepository         $userRepository
     * @param RoomRepository         $roomRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(
        UserRepository $userRepository,
        RoomRepository $roomRepository,
		EntityManagerInterface $em
    ) {
        $this->userRepository = $userRepository;
        $this->roomRepository = $roomRepository;
        $this->em             = $em;
    }

    /**
     * @param AbstractPrivateMessageCommand $command
     * @return Message|null
     */
    public function handle($command):?Message
    {
        $user = $this->userRepository->get($command->getUser());
        $room = $this->roomRepository->findRoomByToken($command->getRoomToken());
        $text = $command->getText();

        if (null === $user || null === $room) {
        	return null;
        }

        $message = new Message(
            Id::next(),
            $user,
            $room,
            $text
        );

        $room->addMessageAndExistParticipant($user, $message);
        $this->em->flush();

        return $message;
    }
}