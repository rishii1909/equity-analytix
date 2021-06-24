<?php


namespace App\Entity\Files\File;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class Info
{
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $path;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;
    /**
     * @var string
     * @ORM\Column(type="integer")
     */
    private $size;

    /**
     * Info constructor.
     *
     * @param string $path
     * @param string $name
     * @param string $size
     */
    public function __construct(string $path, string $name, string $size)
    {
        $this->path = $path;
        $this->name = $name;
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSize(): string
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getFullPath(): string
    {
        return $this->path . '/' . $this->getName();
    }
}
