<?php
declare(strict_types = 1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait TimestampableTrait
 *
 * Entities using this must have HasLifecycleCallbacks annotation.
 */
trait TimestampableTrait
{
    use CreatedAtTrait;
    use UpdatedAtTrait;
}
