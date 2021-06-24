<?php

declare(strict_types=1);

namespace App\Service\Uploader;

use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FileUploader
 *
 * @package App\Service\Uploader
 */
class FileUploader
{
    /**
     * @var string
     */
    private $targetDirectory;

    /**
     * FileUploader constructor.
     *
     * @param string $targetDirectory
     */
    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * @param UploadedFile $file
     *
     * @return File
     */
    public function upload(UploadedFile $file) : File
    {
        $path = $this->targetDirectory . '/' . date('Y-m-d');
        $name = Uuid::uuid4()->toString() . '.' . $file->getClientOriginalExtension();

        $file->move($path, $name);

        return new File($path, $name, $file->getSize());
    }

    /**
     * @return string
     */
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
