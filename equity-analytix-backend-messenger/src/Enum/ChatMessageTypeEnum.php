<?php
declare(strict_types = 1);

namespace App\Enum;

/**
 * Class ChatConnectTypeEnum
 */
class ChatMessageTypeEnum extends AbstractEnum
{
    public const CONNECT = 'connect';
    public const NEWS    = 'news';
    public const PRIVATE = 'private';
}