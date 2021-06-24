<?php


namespace App\UseCases\News\Archive;


use App\Entity\Chat\ArchivedNews\ArchivedNews;
use App\Entity\Chat\News\NewsRepository;
use App\Entity\Chat\User\UserRepository;
use App\UseCases\Flusher;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Class Handler
 *
 * @package   App\Entity\Chat\UseCases\News\Archive
 * @author    Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 */
class Handler
{
    /**
     * @var Flusher
     */
    private $flusher;

    /**
     * @var NewsRepository
     */
    private $newsRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Handler constructor.
     *
     * @param NewsRepository $newsRepository
     * @param UserRepository $userRepository
     * @param Flusher $flusher
     */
    public function __construct(
        NewsRepository $newsRepository,
        UserRepository $userRepository,
        Flusher $flusher
    ) {
        $this->flusher        = $flusher;
        $this->newsRepository = $newsRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param Command $command
     *
     * @throws EntityNotFoundException
     */
    public function handle(Command $command)
    {

        $news = $this->newsRepository->get($command->id);
        $user = $this->userRepository->get($command->userId);
        $archivedNews = new ArchivedNews($news, $user);

        $user->addArchivedNews($archivedNews);

        $this->flusher->flush();
    }

}