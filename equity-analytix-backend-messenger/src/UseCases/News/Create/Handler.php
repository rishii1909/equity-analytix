<?php

declare(strict_types=1);


namespace App\UseCases\News\Create;


use App\Entity\Chat\News\News;
use App\Entity\Chat\News\NewsRepository;
use App\Entity\Chat\News\Status;
use App\Entity\Chat\User\UserRepository;
use App\Entity\Files\File\Entity;
use App\Entity\Files\File\File;
use App\Entity\Files\File\FileRepository;
use App\UseCases\Flusher;

/**
 * Class Handler
 *
 * @package   App\Entity\Chat\UseCases\News\Create
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
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var NewsRepository
     */
    private $newsRepository;
    /**
     * @var FileRepository
     */
    private $files;

    /**
     * Handler constructor.
     *
     * @param NewsRepository $newsRepository
     * @param UserRepository $userRepository
     * @param FileRepository $files
     * @param Flusher        $flusher
     */
    public function __construct(
        NewsRepository $newsRepository,
        UserRepository $userRepository,
        FileRepository $files,
        Flusher $flusher
    )
    {
        $this->flusher        = $flusher;
        $this->userRepository = $userRepository;
        $this->newsRepository = $newsRepository;
        $this->files          = $files;
    }

    /**
     * @param Command $command
     *
     * @return News
     */
    public function handle(Command $command): News
    {
        $user   = $this->userRepository->get($command->user);
        $status = new Status($command->status);

        $news = new News(
            $user,
            $command->text,
            $status
        );

        foreach ($command->attachments as $attachment) {
            $chunks = explode('/', $attachment);
            $path   = $chunks[0] . '/' . $chunks[1];
            $name   = $chunks[2];

            /** @var File $file */
            if ($file = $this->files->findByPathAndName($path, $name)) {
                $file->addEntity(new Entity(News::class, $news->getId()->getValue()));
            }
        }

        $this->newsRepository->add($news);

        $this->flusher->flush();

        return $news;
    }
}
