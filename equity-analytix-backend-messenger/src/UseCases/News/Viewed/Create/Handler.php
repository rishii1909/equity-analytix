<?php


namespace App\UseCases\News\Viewed\Create;


use App\Entity\Chat\News\NewsRepository;
use App\Entity\Chat\ViewedNews\ViewedNews;
use App\Entity\Chat\User\UserRepository;
use App\UseCases\CommandInterface;
use App\UseCases\Flusher;
use App\UseCases\HandlerInterface;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Class Handler
 *
 * @package   App\UseCases\News\Viewed
 * @author    Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 */
class Handler implements HandlerInterface
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
     * @param CommandInterface $command
     *
     * @throws EntityNotFoundException
     */
    public function handle(CommandInterface $command)
    {
        $news = $this->newsRepository->get($command->id);
        $user = $this->userRepository->get($command->userId);
        $viewedNews = new ViewedNews($user, $news);

        $user->addViewedNews($viewedNews);

        $this->flusher->flush();
    }

}