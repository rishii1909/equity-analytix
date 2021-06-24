<?php

namespace App\DataFixtures;

use App\Entity\Chat\User\Role;
use App\Entity\Chat\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class UserFixtures
 *
 * @package App\DataFixtures
 */
class UserFixtures extends Fixture
{
    public const REFERENCE_USER = 'user_user';
    public const REFERENCE_ADMIN = 'user_admin';

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        $admin = User::creteUserFromWP(
            1,
            'adminUser',
            Role::admin()
        );

        $manager->persist($admin);
        $this->setReference(self::REFERENCE_ADMIN, $admin);

        $user = User::creteUserFromWP(
            2,
            'subscriberUser',
            Role::subscriber()
        );

        $manager->persist($user);
        $this->setReference(self::REFERENCE_USER, $user);


        $manager->flush();
    }

}
