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
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * An Email is a sent email to the specified receivers.
 */
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class EraEntry
{
    use IdTrait;

    #[ORM\Column(type: Types::STRING)]
    private ?string $fullName = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $email = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $workMode = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $team = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $generalAgreement = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $lastReminderSent = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $lastChangeAt = null;

    #[ORM\ManyToOne(targetEntity: Era::class, inversedBy: "entries")]
    private ?Era $era = null;

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getWorkMode(): ?string
    {
        return $this->workMode;
    }

    public function setWorkMode(?string $workMode): void
    {
        $this->workMode = $workMode;
    }

    public function getTeam(): ?string
    {
        return $this->team;
    }

    public function setTeam(?string $team): void
    {
        $this->team = $team;
    }

    public function getGeneralAgreement(): ?string
    {
        return $this->generalAgreement;
    }

    public function setGeneralAgreement(?string $generalAgreement): void
    {
        $this->generalAgreement = $generalAgreement;
    }

    public function getLastReminderSent(): ?\DateTimeImmutable
    {
        return $this->lastReminderSent;
    }

    public function setLastReminderSent(?\DateTimeImmutable $lastReminderSent): void
    {
        $this->lastReminderSent = $lastReminderSent;
    }

    public function getLastChangeAt(): ?\DateTimeImmutable
    {
        return $this->lastChangeAt;
    }

    public function setLastChangeAt(?\DateTimeImmutable $lastChangeAt): void
    {
        $this->lastChangeAt = $lastChangeAt;
    }

    public function getEra(): ?Era
    {
        return $this->era;
    }

    public function setEra(?Era $era): void
    {
        $this->era = $era;
    }
}
