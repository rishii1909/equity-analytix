<?php


namespace App\Entity\Chat\ArchivedNews;


use App\Entity\Chat\News\News;
use App\Entity\Chat\User\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

/**
 * @Entity
 * @ORM\Table(name="chat_archived_news")
 */
class ArchivedNews
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=News::class, inversedBy="archivedNews")
     */
    private $newsUnit;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="archivedNews")
     */
    private $user;

    /**
     * ArchivedNews constructor.
     *
     * @param News $newsUnit
     * @param User $user
     */
    public function __construct(News $newsUnit, User $user)
    {
        $this->newsUnit = $newsUnit;
        $this->user = $user;
    }

    /**
     * @return News|null
     */
    public function getNewsUnit(): ?News
    {
        return $this->newsUnit;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

}