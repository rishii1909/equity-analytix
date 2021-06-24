<?php

declare(strict_types=1);


namespace App\DataFixtures;

use App\Entity\Chat\Room\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class RoomFixtures
 *
 * @package App\DataFixtures
 */
class RoomFixtures extends Fixture implements DependentFixtureInterface
{
    public const REFERENCE_PRIVATE_ROOM = 'chat_privat_room';

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $admin = $this->getReference(UserFixtures::REFERENCE_ADMIN);
        $user = $this->getReference(UserFixtures::REFERENCE_USER);

        $private = Room::createPrivateRoom([$admin, $user]);

        $manager->persist($private);
        $this->setReference(self::REFERENCE_PRIVATE_ROOM, $private);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}