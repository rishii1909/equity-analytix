<?php


namespace App\DataFixtures;


use App\Entity\Chat\News\News;
use App\Entity\Chat\News\Status;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class NewsFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $admin = $this->getReference(UserFixtures::REFERENCE_ADMIN);

        $noneNews = new News(
            $admin,
            'Bull admin news text',
            Status::none()
        );
        $manager->persist($noneNews);

        $bullNews = new News(
            $admin,
            'Bull admin news text',
            Status::bull()
        );
        $manager->persist($bullNews);

        $bearNews = new News(
            $admin,
            'Bear admin news text',
            Status::bear()
        );
        $manager->persist($bearNews);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}