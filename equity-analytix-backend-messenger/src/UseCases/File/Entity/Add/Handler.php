<?php

declare(strict_types=1);

namespace App\UseCases\File\Entity\Add;

use App\Entity\Files\File\Entity;
use App\Entity\Files\File\FileRepository;
use App\Entity\Files\File\Id;
use App\UseCases\Flusher;

/**
 * Class Handler
 *
 * @package App\UseCases\File\Entity\Add
 */
class Handler
{
    /**
     * @var FileRepository
     */
    private $files;
    /**
     * @var Flusher
     */
    private $flusher;

    /**
     * Handler constructor.
     *
     * @param FileRepository $files
     * @param Flusher        $flusher
     */
    public function __construct(FileRepository $files, Flusher $flusher)
    {
        $this->files = $files;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command): void
    {
        $file = $this->files->get(new Id($command->id));

        $file->addEntity(new Entity($command->entityType, $command->entityId));

        $this->flusher->flush();
    }
}
