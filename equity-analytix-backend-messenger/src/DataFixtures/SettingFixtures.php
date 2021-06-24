<?php


namespace App\DataFixtures;


use App\Entity\Chat\Setting\Name;
use App\Entity\Chat\Setting\Signification;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SettingFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $user = $this->getReference(UserFixtures::REFERENCE_USER);

        $user->addSetting(
            new Name(Name::NUMBER_OF_FLASHES),
            new Signification('10')
        );


        $user->addSetting(
            new Name(Name::WEB_LINK_COLOR),
            new Signification('#1299da')
        );


        $user->addSetting(
            new Name(Name::STOCK_SYMBOL_COLOR),
            new Signification('#000')
        );

        $user->addSetting(
            new Name(Name::TEXT_SIZE),
            new Signification('13')
        );


        $user->addSetting(
            new Name(Name::TEXT_BOLD),
            new Signification('bold')
        );


        $user->addSetting(
            new Name(Name::INCOMING_MESSAGES),
            new Signification('down')
        );


       $user->addSetting(
            new Name(Name::THEME),
            new Signification('dark')
        );

        $manager->flush();
    }

    public function getDependencies()
    {
      return [
        UserFixtures::class,
      ];
    }
}