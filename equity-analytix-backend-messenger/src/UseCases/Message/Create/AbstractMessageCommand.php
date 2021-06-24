<?php
declare(strict_types = 1);

namespace App\UseCases\Message\Create;

/**
 * Class AbstractMessageCommand
 */
abstract class AbstractMessageCommand implements MessageCommandInterface
{
    /**
     * @var integer
     */
    protected $user;

    /**
     * @var string
     */
    protected $text;

    /**
     * @return integer
     */
    public function getUser(): int
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }
}