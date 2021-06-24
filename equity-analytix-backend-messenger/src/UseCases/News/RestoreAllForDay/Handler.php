<?php

namespace App\UseCases\News\RestoreAllForDay;

use App\Entity\Chat\ArchivedNews\ArchiveNewsRepository;
use App\Entity\Chat\News\NewsRepository;
use App\ReadModel\News\NewsFetcher;
use App\UseCases\CommandInterface;
use App\UseCases\Flusher;
use App\UseCases\HandlerInterface;

class Handler implements HandlerInterface
{

    /**
     * @var NewsRepository
     */
    private $newsRepository;
    /**
     * @var ArchiveNewsRepository
     */
    private $archiveNewsRepository;
    /**
     * @var NewsFetcher
     */
    private $newsFetcher;
    /**
     * @var Flusher
     */
    private $flusher;

    /**
     * Handler constructor.
     *
     * @param NewsRepository $newsRepository
     * @param ArchiveNewsRepository $archiveNewsRepository
     * @param NewsFetcher $newsFetcher
     * @param Flusher $flusher
     */
    public function __construct(
        NewsRepository $newsRepository,
        ArchiveNewsRepository $archiveNewsRepository,
        NewsFetcher $newsFetcher,
        Flusher $flusher
    ) {
        $this->newsRepository = $newsRepository;
        $this->archiveNewsRepository = $archiveNewsRepository;
        $this->newsFetcher = $newsFetcher;
        $this->flusher = $flusher;
    }

    /**
     * @param CommandInterface $command
     *
     * @throws \Doctrine\ORM\EntityNotFoundException
     */
    public function handle(CommandInterface $command)
    {

        if (!$archiveNewsArray =
            $this->newsFetcher->findArchivedUsersNewsForCurrentDay(
                $command->id,
                (new \DateTimeImmutable())->setTimezone(new \DateTimeZone('EST'))
            )) {
            throw new \DomainException("User has not archive News.");
        }

        $archiveNews = array_map(function ($id) {
                return $this->newsRepository->get($id);
            },
            $archiveNewsArray
        );

        foreach ($archiveNews as $newsUnit) {
            $news = $this->archiveNewsRepository->findByNewsUnitId(
                $newsUnit->getId()->getValue()
            );

            $this->archiveNewsRepository->remove($news);
        }

        $this->flusher->flush();
    }
}
