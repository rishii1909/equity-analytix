<?php
declare(strict_types=1);

namespace App\Entity\Chat\Room;

use App\Entity\AbstractTimestampableEntity;
use App\Entity\Chat\Message\Message;
use App\Entity\Chat\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * Class ChatRoom
 *
 * @ORM\Entity(repositoryClass="App\Repository\RoomRepository")
 * @ORM\Table(name="chat_room_rooms")
 * @ORM\HasLifecycleCallbacks()
 */
class Room extends AbstractTimestampableEntity
{
    public const PRIVATE_ROOM = 'private';

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\Chat\User\User", inversedBy="rooms")
     * @ORM\JoinTable(name="chat_rooms_users")
     */
    private $participants;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Chat\Message\Message", mappedBy="chatRoom", cascade={"persist"})
     */
    private $messages;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $type;

	/**
	 * @var string
	 * @ORM\Column(type="string", unique=true)
	 */
	private $token;

    /**
     * Room constructor.
     */
    public function __construct()
    {
        $this->messages  = new ArrayCollection();
    }

    /**
     * @param array $participants
     * @return Room
     */
    public static function createPrivateRoom(array $participants): Room
    {
        $room               = new self();
        $room->participants = new ArrayCollection($participants);
        $room->type         = self::PRIVATE_ROOM;
        $room->token        = Uuid::uuid4()->toString();

        return $room;
    }

    /**
     * @param Message $message
     *
     * @return $this
     */
    public function addMessage(Message $message):Room
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
        }

        return $this;
    }

	/**
	 * @param User $user
	 * @param Message $message
	 * @return Room
	 */
	public function addMessageAndExistParticipant(User $user, Message $message):Room
	{
		if ($this->participants->contains($user)) {
			$this->addMessage($message);
		}

		return $this;
	}

    /**
     * @param User $user
     *
     * @return $this
     */
    public function addParticipant(User $user):Room
    {
        if (!$this->participants->contains($user)) {
            $this->participants->add($user);
            $user->addRoom($this);
        }

        return $this;
    }

	/**
	 * @param User $user
	 *
	 * @return $this
	 */
	public function removeParticipant(User $user):Room
	{
		if ($this->participants->contains($user)) {
			$this->participants->removeElement($user);
		}

		return $this;
	}

    /**
     * @return ArrayCollection[]
     */
    public function getParticipants(): array
    {
        return $this->participants->toArray();
    }

    /**
     * @return ArrayCollection[]
     */
    public function getParticipantIds(): array
    {
        return $this->participants->map(function (User $user) {
            return $user->getId();
        })->toArray();
    }

    /**
     * @return ArrayCollection[]
     */
    public function getMessages(): array
    {
        return $this->messages->toArray();
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

	/**
	 * @return string
	 */
	public function getToken(): string
	{
		return $this->token;
	}

	/**
	 * @param string $token
	 * @return Room
	 */
	public function setToken(string $token): self
	{
		$this->token = $token;

		return $this;
	}
}