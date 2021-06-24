<?php
declare(strict_types = 1);

namespace App\UseCases\Message\Create;

/**
 * Interface MessageCommandInterface
 */
interface MessageCommandInterface
{
    /**
     * @return integer
     */
    public function getUser(): int;

    /**
     * @return string
     */
    public function getText(): string;
}