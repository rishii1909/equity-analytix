<?php
declare(strict_types = 1 );
namespace App\Entity\Chat\User;

use App\Entity\Chat\ArchivedNews\ArchivedNews;
use App\Entity\Chat\Message\Message;
use App\Entity\Chat\News\News;
use App\Entity\Chat\Room\Room;
use App\Entity\Chat\Setting\Name;
use App\Entity\Chat\Setting\Setting;
use App\Entity\Chat\Setting\Signification;
use App\Entity\Chat\ViewedMessage\ViewedMessage;
use App\Entity\Chat\ViewedNews\ViewedNews;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DomainException;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="chat_user_users")
 */
class User
{
    public const STATUS_ACTIVE = true;
    public const STATUS_BLOCKED = false;

    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", name="user_name", nullable=false)
     */
    private $userName;

    /**
     * @var Role
     * @ORM\Column(type="chat_user_role")
     */
    private $role;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Chat\Message\Message", mappedBy="user")
     */
    private $messages;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Chat\News\News", mappedBy="user")
     */
    private $news;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\Chat\Room\Room", mappedBy="participants")
     */
    private $rooms;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="created_at")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=ArchivedNews::class, mappedBy="user", cascade={"persist"})
     */
    private $archivedNews;

    /**
     * @ORM\OneToMany(targetEntity=Setting::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $settings;

    /**
     * @ORM\OneToMany(targetEntity=ViewedNews::class, mappedBy="user", cascade={"persist"})
     */
    private $viewedNews;

    /**
     * @ORM\OneToMany(targetEntity=ViewedMessage::class, mappedBy="user", cascade={"persist"})
     */
    private $viewedMessages;

    /**
     * User constructor.
     *
     * @param int $id
     * @param string $userName
     * @param Role $role
     */
    public function __construct(int $id, string $userName, Role $role)
    {
        $this->id = $id;
        $this->userName = $userName;
        $this->role = $role;
        $this->messages = new ArrayCollection();
        $this->news = new ArrayCollection();
        $this->rooms = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
        $this->archivedNews = new ArrayCollection();
        $this->settings = new ArrayCollection();
        $this->viewedNews = new ArrayCollection();
        $this->viewedMessages = new ArrayCollection();
    }

    /**
     * @param int $id
     * @param string $userName
     * @param Role $role
     *
     * @return User
     */
    public static function creteUserFromWP(int $id, string $userName, Role $role): User
    {
        $user = new self($id, $userName, $role);
        $user->id = $id;
        $user->status = self::STATUS_ACTIVE;

        return $user;
    }

    /**
     * @param Message $message
     */
    public function addMessage(Message $message)
    {
        $this->messages->add($message);
    }

    /**
     * @param News $news
     */
    public function addNews(News $news)
    {
        $this->news->add($news);
    }

    public function addRoom(Room $room)
    {
        $this->rooms->add($room);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role->getValue();
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role->isAdmin();
    }

    /**
     * @return ArrayCollection[]
     */
    public function getMessages(): array
    {
        return $this->messages->toArray();
    }

    /**
     * @return ArrayCollection[]
     */
    public function getRooms(): array
    {
        return $this->rooms->toArray();
    }

    /**
     * @return bool
     */
    public function isStatus(): bool
    {
        return $this->status;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return ArrayCollection[]
     */
    public function getArchivedNews(): array
    {
        return $this->archivedNews->toArray();
    }

    /**
     * @param ArchivedNews $archivedNews
     */
    public function addArchivedNews(ArchivedNews $archivedNews): void
    {
        if ($this->getFilteredArchives($archivedNews->getNewsUnit())->count()) {
            throw new DomainException("This news already archived.");
        }

        $this->archivedNews->add($archivedNews);
    }


    /**
     * @return ArrayCollection
     */
    public function getFilteredArchives(News $newsItem): ArrayCollection
    {
        return $this->archivedNews->filter(function (ArchivedNews $item) use ($newsItem) {
            return ($item->getNewsUnit()->getId() === $newsItem->getId());
        });
    }

    /**
     * @return Collection|Setting[]
     */
    public function getSettings(): Collection
    {
        return $this->settings;
    }

    /**
     * @param Name $name
     * @param Signification $signification
     *
     * @return Setting
     */
    public function addSetting(Name $name, Signification $signification): Setting
    {
        foreach ($this->settings as $setting) {
            if ($setting->isNameEqual($name)) {
                throw new DomainException('Setting is already exists.');
            }
        }
        $this->settings->add($setting = new Setting($name, $signification, $this));

        return $setting;
    }

    /**
     * @param int $id
     * @param Signification $signification
     */
    public function editSetting(int $id, Signification $signification): void
    {
        foreach ($this->settings as $setting) {
            if ($setting->isIdEqual($id)) {
                $setting->edit($signification);
                return;
            }
        }

        throw new DomainException("Setting is not found.");
    }

    /**
     * @return Collection|ViewedNews[]
     */
    public function getViewedNews(): Collection
    {
        return $this->viewedNews;
    }

    /**
     * @param ViewedNews $viewedNews
     */
    public function addViewedNews(ViewedNews $viewedNews):void
    {
        if ($this->getFilteredViewed($viewedNews->getNewsUnit())->count()) {
            throw new DomainException("This news already viewed.");
        }

        $this->viewedNews->add($viewedNews);
    }

    /**
     * @return ArrayCollection
     */
    public function getFilteredViewed(News $newsItem): ArrayCollection
    {
        return $this->viewedNews->filter(function (ViewedNews $item) use ($newsItem) {
            return ($item->getNewsUnit()->getId() === $newsItem->getId());
        });
    }

    /**
     * @param ViewedMessage $viewedMessage
     */
    public function addViewedMessage(ViewedMessage $viewedMessage): void
    {
        foreach ($this->getViewedMessages() as $message) {
            if ($message->isEqual($viewedMessage)) {
                throw new DomainException('Message is already viewed.');
            }
        }

        $this->viewedMessages->add($viewedMessage);
    }

    /**
     * @return Collection|ViewedMessage[]
     */
    public function getViewedMessages()
    {
        return $this->viewedMessages;
    }
}
