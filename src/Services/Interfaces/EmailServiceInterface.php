<?php

namespace App\Services\Interfaces;

use App\Entity\EraEntry;

interface EmailServiceInterface
{
    public function announceEra(EraEntry $entry): bool;
}
