<?php

/*
 * This file is part of the baupen project.
 *
 * (c) Florian Moser <git@famoser.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/*
 * automatically keeps track of creation time & last change time
 */

trait TimeTrait
{
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $lastChangedAt = null;

    /**
     * @throws \Exception
     * @throws \Exception
     */
    #[ORM\PrePersist]
    public function prePersistTime(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->lastChangedAt = new \DateTimeImmutable();
    }

    /**
     * @throws \Exception
     */
    #[ORM\PreUpdate]
    public function preUpdateTime(): void
    {
        $this->lastChangedAt = new \DateTimeImmutable();
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getLastChangedAt(): ?\DateTimeImmutable
    {
        return $this->lastChangedAt;
    }
}
