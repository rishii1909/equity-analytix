<?php

declare(strict_types=1);


namespace App\DataFixtures;


use App\Entity\Chat\Message\Id;
use App\Entity\Chat\Message\Message;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class MessageFixtures
 *
 * @package App\DataFixtures
 */
class MessageFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $user = $this->getReference(UserFixtures::REFERENCE_USER);
        $admin = $this->getReference(UserFixtures::REFERENCE_ADMIN);

        $privatRoom = $this->getReference(RoomFixtures::REFERENCE_PRIVATE_ROOM);

        $userMessagePrivRoom = new Message(
            Id::next(),
            $user,
            $privatRoom,
            'User message in private room'
        );

        $manager->persist($userMessagePrivRoom);

        $adminMessagePrivRoom = new Message(
        Id::next(),
        $admin,
        $privatRoom,
        'Admin message in private room'
        );

        $manager->persist($adminMessagePrivRoom);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            RoomFixtures::class,
        ];
    }
}