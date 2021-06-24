<?php
declare(strict_types = 1);

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class AbstractTimestampableEntity
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class AbstractTimestampableEntity
{
    use TimestampableTrait;

	/**
	 * @var int
	 * @ORM\Id()
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 *
	 */
	protected $id;

    /**
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}
