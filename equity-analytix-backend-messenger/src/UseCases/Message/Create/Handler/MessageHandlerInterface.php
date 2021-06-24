<?php
declare(strict_types = 1);

namespace App\UseCases\Message\Create\Handler;

use App\Entity\Chat\Message\Message;
use App\UseCases\Message\Create\AbstractMessageCommand;
use App\UseCases\Message\Create\AbstractPrivateMessageCommand;

/**
 * Interface MessageHandlerInterface
 */
interface MessageHandlerInterface
{
    /**
     * @param AbstractMessageCommand|AbstractPrivateMessageCommand $command
     * @return Message|null
     */
    public function handle($command): ?Message;
}