<?php

declare(strict_types=1);


namespace App\UseCases\Room\Create;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 *
 * @package   App\Entity\Chat\UseCases\Room\Create
 * @author    Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 */
class Command
{
    /**
     * @Assert\Count(
     *      min = 2,
     *      max = 2,
     *      minMessage = "You must specify at least one participant",
     *      maxMessage = "You cannot specify more than {{ limit }} emails"
     * )
     * @var array
     */
    private $participants;

    /**
     * @return array
     */
    public function getParticipants():array
    {
        return $this->participants;
    }

    /**
     * @param array $participants
     */
    public function setParticipants(array $participants): void
    {
        $this->participants = $participants;
    }

    /**
     * @return mixed
     */
    public function getFirstParticipant()
    {
        return $this->getParticipants()[0];
    }

    /**
     * @return mixed
     */
    public function getSecondParticipant()
    {
        return $this->getParticipants()[1];
    }
}