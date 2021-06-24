<?php
declare(strict_types = 1);

namespace App\UseCases\Room\Create;

use App\Entity\Chat\Room\Room;
use App\Entity\Chat\User\User;
use App\Entity\Chat\User\UserRepository;
use App\Repository\RoomRepository;
use App\UseCases\Flusher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class Handler
 */
class Handler
{
    /** @var RoomRepository */
    private $roomRepository;
    /** @var UserRepository */
    private $userRepository;
	/**@var Flusher */
	private $em;

    /**
     * @param RoomRepository         $roomRepository
     * @param UserRepository         $userRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(
        RoomRepository $roomRepository,
        UserRepository $userRepository,
		EntityManagerInterface $em
    ) {
	    $this->roomRepository = $roomRepository;
	    $this->userRepository = $userRepository;
	    $this->em             = $em;
    }

    /**
     * @param Command $command
     * @return Room
     */
    public function handle(Command $command):Room
    {
        $user = $this->userRepository->get($command->getFirstParticipant())->isAdmin() ?
            $command->getSecondParticipant() : $command->getFirstParticipant();

        if ($this->roomRepository->hasPrivateChat($user)) {
        	$room = $this->roomRepository->findPrivateRoomForUser($command->getFirstParticipant(), $command->getSecondParticipant());

        	if ($room instanceof Room) {
        		return $room;
	        }

            throw new NotFoundHttpException("Room not found. Please create room again!.");
        }

        $participants = array_map(
            function (int $id): User {
                return $this->userRepository->get($id);
            },
            $command->getParticipants()
        );

        $room = Room::createPrivateRoom($participants);

	    $this->em->persist($room);
	    $this->em->flush();

	    return $room;
    }
}