<?php

namespace App\Entity\Chat\News;

use App\Entity\Chat\ArchivedNews\ArchivedNews;
use App\Entity\Chat\User\User;
use App\Entity\Chat\ViewedNews\ViewedNews;
use App\Entity\Files\File\File;
use DateTimeZone;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="chat_news_news")
 */
class News
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="chat_news_id", unique=true)
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Chat\User\User", inversedBy="news")
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     */
    private $text;


    /**
     * @var Status
     * @ORM\Column(type="chat_news_status")
     */
    private $status;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=ArchivedNews::class, mappedBy="newsUnit")
     */
    private $archivedNews;

    /**
     * @ORM\OneToMany(targetEntity=ViewedNews::class, mappedBy="newsUnit")
     */
    private $viewedNews;

    /**
     * News constructor.
     *
     * @param User $user
     * @param string $text
     * @param Status $status
     */
    public function __construct(User $user, string $text, Status $status)
    {
        $this->id = Id::next();
        $this->user = $user;
        $this->text = $text;
        $this->status = $status;
        $this->createdAt = (new \DateTimeImmutable('now'))->setTimezone(new DateTimeZone('EST'));
        $this->archivedNews = new ArrayCollection();
        $this->viewedNews = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status->getName();
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
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
     * @return Collection|ArchivedNews[]
     */
    public function getArchivedNews(): Collection
    {
        return $this->archivedNews;
    }

    /**
     * @return Collection|ViewedNews[]
     */
    public function getViewedNews(): Collection
    {
        return $this->viewedNews;
    }

    /**
     * @return string
     */
    public function getMessageType(): string
    {
        return 'news';
    }
}
