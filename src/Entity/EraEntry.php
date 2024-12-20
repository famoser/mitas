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
    use TimeTrait;

    #[ORM\Column(type: Types::STRING)]
    private ?string $fullName = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $email = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $workMode = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $team = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $generalAgreement = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $vacationsInEra = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $absences = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $timeOffRequests = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comments = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $lastReminderSent = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $lastConfirmedAt = null;

    #[ORM\ManyToOne(targetEntity: Era::class, inversedBy: 'entries')]
    private ?Era $era = null;

    public static function copyPersistentFields(EraEntry $copyEntry): EraEntry
    {
        $self = new self();
        $self->setFullName($copyEntry->getFullName());
        $self->setEmail($copyEntry->getEmail());
        $self->setWorkMode($copyEntry->getWorkMode());
        $self->setTeam($copyEntry->getTeam());
        $self->setGeneralAgreement($copyEntry->getGeneralAgreement());

        return $self;
    }

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

    public function getVacationsInEra(): ?bool
    {
        return $this->vacationsInEra;
    }

    public function setVacationsInEra(?bool $vacationsInEra): void
    {
        $this->vacationsInEra = $vacationsInEra;
    }

    public function getAbsences(): ?string
    {
        return $this->absences;
    }

    public function setAbsences(?string $absences): void
    {
        $this->absences = $absences;
    }

    public function getTimeOffRequests(): ?string
    {
        return $this->timeOffRequests;
    }

    public function setTimeOffRequests(?string $timeOffRequests): void
    {
        $this->timeOffRequests = $timeOffRequests;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): void
    {
        $this->comments = $comments;
    }

    public function getLastReminderSent(): ?\DateTimeImmutable
    {
        return $this->lastReminderSent;
    }

    public function setLastReminderSent(): void
    {
        $this->lastReminderSent = new \DateTimeImmutable();
    }

    public function getLastConfirmedAt(): ?\DateTimeImmutable
    {
        return $this->lastConfirmedAt;
    }

    public function setLastConfirmedAt(): void
    {
        $this->lastConfirmedAt = new \DateTimeImmutable();
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
