<?php
declare(strict_types = 1);

namespace App\UseCases\Message\Create;

/**
 * Class AbstractMessageCommand
 */
abstract class AbstractPrivateMessageCommand extends AbstractMessageCommand
{
    /**
     * @var string
     */
    protected $roomToken;

    /**
     * @return string
     */
    public function getRoomToken(): string
    {
        return $this->roomToken;
    }
}