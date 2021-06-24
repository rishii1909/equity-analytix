<?php

declare(strict_types=1);


namespace App\UseCases\News\ArchiveAllForDay;


use App\Entity\Chat\ArchivedNews\ArchivedNews;
use App\Entity\Chat\ArchivedNews\ArchiveNewsRepository;
use App\Entity\Chat\News\NewsRepository;
use App\UseCases\CommandInterface;
use App\Entity\Chat\User\UserRepository;
use App\UseCases\Flusher;
use App\ReadModel\News\NewsFetcher;
use App\UseCases\HandlerInterface;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityNotFoundException;
use DomainException;

class Handler implements HandlerInterface
{
    /**
     * @var NewsRepository
     */
    private $newsRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var Flusher
     */
    private $flusher;
    /**
     * @var NewsFetcher
     */
    private $newsFetcher;

    /**
     * @var ArchiveNewsRepository
     */
    private $archiveNewsRepository;

    /**
     * Handler constructor.
     *
     * @param NewsRepository        $newsRepository
     * @param ArchiveNewsRepository $archiveNewsRepository
     * @param UserRepository        $userRepository
     * @param NewsFetcher           $newsFetcher
     * @param Flusher               $flusher
     */
    public function __construct(
        NewsRepository $newsRepository,
        ArchiveNewsRepository $archiveNewsRepository,
        UserRepository $userRepository,
        NewsFetcher $newsFetcher,
        Flusher $flusher)
    {
        $this->newsRepository        = $newsRepository;
        $this->userRepository        = $userRepository;
        $this->flusher               = $flusher;
        $this->newsFetcher           = $newsFetcher;
        $this->archiveNewsRepository = $archiveNewsRepository;
    }

    /**
     * @param CommandInterface $command
     *
     * @throws EntityNotFoundException
     */
    public function handle(CommandInterface $command)
    {
        $user = $this->userRepository->get($command->id);

        if (!$activeNews = $this->newsFetcher->findActiveUserNewsForCurrentDay(
            $command->id,
            (new DateTimeImmutable())->setTimezone(new DateTimeZone('EST'))
        )
        ) {
            throw new DomainException('User has not active News.');
        }

        foreach ($activeNews as $existence) {
            $news         = $this->newsRepository->get($existence);
            $archivedNews = new ArchivedNews($news, $user);

            $this->archiveNewsRepository->add($archivedNews);
        }

        $this->flusher->flush();
    }
}
