<?php


namespace App\Entity\Chat\ArchivedNews;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Class ArchiveNewsRepository
 *
 * @package   App\Entity\Chat\ArchivedNews
 * @author    Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 */
class ArchiveNewsRepository
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var \Doctrine\Persistence\ObjectRepository
     */
    private $repo;

    /**
     * MessageRepository constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(ArchivedNews::class);
        $this->em   = $em;
    }

    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     * @return ArchivedNews
     */
    public function get($id): ArchivedNews
    {
        /** @var ArchivedNews $archivedNews */
        if (!$archivedNews = $this->repo->find($id)) {
            throw new EntityNotFoundException('News not found.');
        }

        return $archivedNews;
    }

    /**
     * @param $id
     *
     * @throws EntityNotFoundException
     * @return ArchivedNews
     */
    public function findByNewsUnitId($id): ArchivedNews
    {
        /** @var ArchivedNews $archivedNews */
        if (!$archivedNews = $this->repo->findOneBy(['newsUnit' => $id])) {
            throw new EntityNotFoundException('News not found.');
        }

        return $archivedNews;
    }

    /**
     * @param ArchivedNews $archivedNews
     */
    public function add(ArchivedNews $archivedNews)
    {
        $this->em->persist($archivedNews);
    }

    /**
     * @param ArchivedNews $archivedNews
     */
    public function remove(ArchivedNews $archivedNews)
    {
        $this->em->remove($archivedNews);
    }

}