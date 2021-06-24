<?php
declare(strict_types=1);

namespace App\UseCases\Message\Create;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class PrivateMessageCommand
 */
class PrivateMessageCommand extends AbstractPrivateMessageCommand
{
    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     */
    protected $user;

	/**
	 * @Assert\NotBlank()
	 * @Assert\Type(type="string")
	 * @Assert\Length(max="65535")
	 */
    protected $text;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    protected $roomToken;

    /**
     * @param integer $user
     * @param string  $text
     * @param string  $roomToken
     */
    public function __construct(int $user, string $text, string $roomToken)
    {
        $this->user       = $user;
        $this->roomToken  = $roomToken;
        $this->text       = $text;
    }
}
