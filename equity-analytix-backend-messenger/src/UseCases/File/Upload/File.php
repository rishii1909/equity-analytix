<?php

declare(strict_types=1);

namespace App\UseCases\File\Upload;

/**
 * Class File
 *
 * @package App\UseCases\File\Upload
 */
class File
{
    /**
     * @var string
     */
    public $path;
    /**
     * @var string
     */
    public $name;
    /**
     * @var int
     */
    public $size;

    /**
     * File constructor.
     *
     * @param string $path
     * @param string $name
     * @param int    $size
     */
    public function __construct(string $path, string $name, int $size)
    {
        $this->path = $path;
        $this->name = $name;
        $this->size = $size;
    }
}
