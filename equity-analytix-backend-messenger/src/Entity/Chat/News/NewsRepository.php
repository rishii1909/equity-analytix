<?php


namespace App\Entity\Chat\News;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Class NewsRepository
 *
 * @package   App\Entity\Chat\News
 * @author    Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 */
class NewsRepository
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var \Doctrine\Persistence\ObjectRepository
     */
    private $repo;

    /**
     * MessageRepository constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(News::class);
        $this->em = $em;
    }

    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     * @return News
     */
    public function get($id): News
    {
        /** @var News $news */
        if (!$news = $this->repo->find($id)) {
            throw new EntityNotFoundException('News not found');
        }

        return $news;
    }

    /**
     * @param News $news
     */
    public function add(News $news)
    {
        $this->em->persist($news);
    }
}