<?php

declare(strict_types=1);

namespace App\Entity\Chat\ViewedMessage;

use App\Entity\Chat\Message\Message;
use App\Entity\Chat\User\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ViewedMessage
 *
 * @package App\Entity\Chat\ViewedMessage
 * @ORM\Entity()
 * @ORM\Table(name="chat_viewed_messages")
 */
class ViewedMessage
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Message::class, inversedBy="viewedMessages")
     */
    private $messageUnit;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="viewedMessages")
     */
    private $user;

    /**
     * ViewedNews constructor.
     *
     * @param User    $user
     * @param Message $messageUnit
     */
    public function __construct(User $user, Message $messageUnit)
    {
        $this->user = $user;
        $this->messageUnit = $messageUnit;
    }

    /**
     * @param ViewedMessage $message
     *
     * @return bool
     */
    public function isEqual(self $message): bool
    {
        return $this->getMessageUnit()->getId()->getValue() === $message->getMessageUnit()->getId()->getValue();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Message|null
     */
    public function getMessageUnit(): ?Message
    {
        return $this->messageUnit;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }
}
