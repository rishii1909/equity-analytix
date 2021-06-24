<?php


namespace App\Entity\Chat\ViewedNews;

use App\Entity\Chat\News\News;
use App\Entity\Chat\User\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ViewedNews
 *
 * @package   App\Entity\Chat\News\ViewedNews
 * @author    Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 * @ORM\Entity()
 * @ORM\Table(name="chat_viewed_news")
 */
class ViewedNews
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=News::class, inversedBy="viewedNews")
     */
    private $newsUnit;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="viewedNews")
     */
    private $user;

    /**
     * ViewedNews constructor.
     *
     * @param User $user
     * @param News $newsUnit
     */
    public function __construct(User $user, News $newsUnit)
    {
        $this->user = $user;
        $this->newsUnit = $newsUnit;
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