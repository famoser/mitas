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
use App\Entity\Traits\TimeTrait;
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
    use TimeTrait;

    #[ORM\Column(type: Types::STRING)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $deadlineAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $announcedAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $reminderSentAt = null;

    /**
     * @var Collection<string, EraEntry>
     */
    #[ORM\OneToMany(targetEntity: EraEntry::class, mappedBy: 'era')]
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

    public function getDeadlineAt(): ?\DateTimeImmutable
    {
        return $this->deadlineAt;
    }

    public function isDeadlineToday(): bool
    {
        if (!$this->deadlineAt) {
            return false;
        }

        $now = new \DateTime();
        $cutoff = $this->deadlineAt->add(new \DateInterval('P1D'));

        return $this->deadlineAt < $now && $now < $cutoff;
    }

    public function isDeadlinePassed(): bool
    {
        if (!$this->deadlineAt) {
            return false;
        }

        $now = new \DateTime();
        $cutoff = $this->deadlineAt->add(new \DateInterval('P1D'));

        return $cutoff < $now;
    }

    public function setDeadlineAt(?\DateTimeImmutable $deadlineAt): void
    {
        $this->deadlineAt = $deadlineAt;
    }

    public function getAnnouncedAt(): ?\DateTimeImmutable
    {
        return $this->announcedAt;
    }

    public function setAnnouncedAt(): void
    {
        $this->announcedAt = new \DateTimeImmutable();
    }

    public function getReminderSentAt(): ?\DateTimeImmutable
    {
        return $this->reminderSentAt;
    }

    public function setReminderSentAt(): void
    {
        $this->reminderSentAt = new \DateTimeImmutable();
    }

    /**
     * @return Collection<string, EraEntry>
     */
    public function getEntries(): Collection
    {
        return $this->entries;
    }
}
