<?php

declare(strict_types=1);

namespace App\UseCases\File\Upload;

use App\UseCases\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 *
 * @package App\UseCases\File\Upload
 */
class Command implements CommandInterface
{
    /**
     * @var File[]
     * @Assert\NotBlank()
     */
    public $files;

    /**
     * Command constructor.
     *
     * @param array $files
     */
    public function __construct(array $files)
    {
        $this->files = $files;
    }
}
