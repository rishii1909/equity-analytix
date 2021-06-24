<?php

declare(strict_types=1);

namespace App\Entity\Files\File;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Class FileRepository
 *
 * @package App\Entity\Files\File
 */
class FileRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var EntityRepository
     */
    private $repo;

    /**
     * UserRepository constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(File::class);
        $this->em   = $em;
    }

    /**
     * @param Id $id
     *
     * @return File
     */
    public function get(Id $id): File
    {
        /** @var File $file */
        if (!$file = $this->repo->find($id->getValue())) {
            throw new \DomainException('File is not found.');
        }

        return $file;
    }

    /**
     * @param string $path
     * @param string $name
     *
     * @return File|null
     */
    public function findByPathAndName(string $path, string $name): ?File
    {
        /** @var File $file */
        $file = $this->repo->findOneBy([
            'info.path' => $path,
            'info.name' => $name,
        ]);

        return $file;
    }

    /**
     * @param string $id
     *
     * @return File|null
     */
    public function findByEntityId(string $id): ?File
    {
        /** @var File $file */
        $file = $this->repo->findOneBy(['entity.id' => $id]);

        return $file;
    }

    /**
     * @param File $file
     */
    public function add(File $file): void
    {
        $this->em->persist($file);
    }
}
