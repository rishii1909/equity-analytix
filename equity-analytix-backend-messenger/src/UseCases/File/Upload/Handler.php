<?php

declare(strict_types=1);

namespace App\UseCases\File\Upload;

use App\Entity\Files\File\File;
use App\Entity\Files\File\FileRepository;
use App\Entity\Files\File\Id;
use App\Entity\Files\File\Info;
use App\UseCases\CommandInterface;
use App\UseCases\Flusher;
use App\UseCases\HandlerInterface;

/**
 * Class Handler
 *
 * @package App\UseCases\File\Upload
 */
class Handler implements HandlerInterface
{
    /**
     * @var FileRepository
     */
    private $files;
    /**
     * @var Flusher
     */
    private $flusher;

    public function __construct(FileRepository $files, Flusher $flusher)
    {
        $this->files = $files;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     */
    public function handle(CommandInterface $command): void
    {
        foreach ($command->files as $file) {
            $info = new Info($file->path, $file->name, (string)$file->size);

            $file = new File(
                Id::next(),
                new \DateTimeImmutable(),
                $info
            );

            $this->files->add($file);
        }

        $this->flusher->flush();
    }
}
