<?php


namespace App\Entity\Chat\Setting;

use App\Entity\Chat\User\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Settings
 *
 * @package   App\Entity\Chat\Settings
 * @author    Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 * @ORM\Entity()
 * @ORM\Table(name="chat_setting_settings")
 */
class Setting
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    private $id;

    /**
     * @var Name
     * @ORM\Column(type="chat_setting_name")
     */
    private $name;

    /**
     * @var Signification
     * @ORM\Column(type="chat_setting_signification")
     */
    private $signification;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="settings")
     */
    private $user;

    /**
     * Setting constructor.
     *
     * @param Name $name
     * @param Signification $signification
     * @param User $user
     */
    public function __construct(Name $name, Signification $signification, User $user)
    {
        $this->name = $name;
        $this->signification = $signification;
        $this->user = $user;
    }

    /**
     * @param Signification $signification
     */
    public function edit(Signification $signification)
    {
        $this->signification = $signification;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name->getName();
    }

    /**
     * @return string
     */
    public function getSignification(): string
    {
        return $this->signification->getValue();
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param Name $name
     *
     * @return bool
     */
    public function isNameEqual(Name $name): bool
    {
       return  $this->name->isEqual($name);
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function isIdEqual(int $id): bool
    {
        return $this->getId() === $id;
    }

}
