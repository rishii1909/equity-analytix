<?php


namespace App\UseCases\User\Setting\Create;


use App\Entity\Chat\Setting\Name;
use App\Entity\Chat\Setting\Signification;
use App\Entity\Chat\User\UserRepository;
use App\UseCases\CommandInterface;
use App\UseCases\Flusher;
use App\UseCases\HandlerInterface;

/**
 * Class Handler
 *
 * @package   App\UseCases\User\Setting\Create
 * @author    Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 */
class Handler implements HandlerInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var Flusher
     */
    private $flusher;

    /**
     * Handler constructor.
     *
     * @param UserRepository $userRepository
     * @param Flusher $flusher
     */
    public function __construct(UserRepository $userRepository, Flusher $flusher)
    {
        $this->userRepository = $userRepository;
        $this->flusher = $flusher;
    }


    /**
     * @param CommandInterface $command
     */
    public function handle(CommandInterface $command)
    {
        $user = $this->userRepository->get($command->userId);
        $name = new Name($command->name);
        $signification = new Signification($command->signification);

        $setting = $user->addSetting(
            $name,
            $signification
        );

        $this->flusher->flush();

        return $setting;
    }
}