<?php

declare(strict_types=1);


namespace App\UseCases\User\Create;

use App\UseCases\CommandInterface;
use App\UseCases\HandlerInterface;
use App\Entity\Chat\User\Role;
use App\Entity\Chat\User\User;
use App\Entity\Chat\User\UserRepository;
use App\UseCases\Flusher;

/**
 * Class Handler
 *
 * @package   App\Entity\Chat\UseCases\User\Create
 * @author    Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 */
class Handler implements HandlerInterface
{

    /**
     * @var Flusher
     */
    private $flusher;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Handler constructor.
     *
     * @param UserRepository $userRepository
     * @param Flusher $flusher
     */
    public function __construct(
        UserRepository $userRepository,
        Flusher $flusher
    ) {
        $this->flusher        = $flusher;
        $this->userRepository = $userRepository;
    }

    /**
     * @param CommandInterface $command
     */
    public function handle(CommandInterface $command)
    {
        $role = new Role($command->role);
        if ($user = $this->userRepository->has((int)$command->userId)) {
            throw new \DomainException('User already exists.');
        }

        $user = User::creteUserFromWP(
            (int)$command->userId,
            $command->userName,
            $role
        );

        $this->userRepository->add($user);

        $this->flusher->flush();
    }
}