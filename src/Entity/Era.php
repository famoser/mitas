<?php

/*
 * This file is part of the baupen project.
 *
 * (c) Florian Moser <git@famoser.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * An Email is a sent email to the specified receivers.
 */
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Era
{
    use IdTrait;

    #[ORM\Column(type: Types::STRING)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $lastReminderSent = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $deadlineAt = null;

    /**
     * @var Collection<string, EraEntry>
     */
    #[ORM\OneToMany(targetEntity: EraEntry::class, mappedBy: "era")]
    private Collection $entries;

    public function __construct()
    {
        $this->entries = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getLastReminderSent(): ?\DateTimeImmutable
    {
        return $this->lastReminderSent;
    }

    public function setLastReminderSent(?\DateTimeImmutable $lastReminderSent): void
    {
        $this->lastReminderSent = $lastReminderSent;
    }

    public function getDeadlineAt(): ?\DateTimeImmutable
    {
        return $this->deadlineAt;
    }

    public function setDeadlineAt(?\DateTimeImmutable $deadlineAt): void
    {
        $this->deadlineAt = $deadlineAt;
    }

    /**
     * @return Collection<string, EraEntry>
     */
    public function getEntries(): Collection
    {
        return $this->entries;
    }
}
