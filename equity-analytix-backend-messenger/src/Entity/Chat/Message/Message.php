<?php

namespace App\Entity\Chat\Message;

use App\Entity\Chat\Room\Room;
use App\Entity\Chat\User\User;
use App\Entity\Chat\ViewedMessage\ViewedMessage;
use DateTimeZone;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="chat_message_messages")
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="chat_message_id", unique=true)
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Chat\User\User", inversedBy="messages")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Chat\Room\Room", inversedBy="messages")
     */
    private $chatRoom;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=ViewedMessage::class, mappedBy="messageUnit")
     */
    private $viewedMessages;

    /**
     * @param Id $id
     * @param User $user
     * @param Room $chatRoom
     * @param string $text
     */
    public function __construct(
        Id $id,
        User $user,
        Room $chatRoom,
        string $text
    ) {
        $this->id        = $id;
        $this->user      = $user;
        $this->chatRoom  = $chatRoom;
        $this->text      = $text;
        $this->createdAt = (new \DateTimeImmutable('now'))->setTimezone(new DateTimeZone('EST'));
        $this->viewedMessages = new ArrayCollection();
    }

    /**
     * @param Room $room
     *
     * @return bool
     */
    public function isForRoom(Room $room): bool
    {
        return $this->chatRoom->getId() === $room->getId();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return Room
     */
    public function getChatRoom(): Room
    {
        return $this->chatRoom;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->user->getUserName();
    }

    /**
     * @return int
     */
    public function getRoomId()
    {
        return $this->chatRoom->getId();
    }

    /**
     * @return Collection|ViewedMessage[]
     */
    public function getViewedMessages()
    {
        return $this->viewedMessages;
    }
}
