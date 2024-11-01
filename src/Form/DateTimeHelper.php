<?php

namespace App\Form;

use IntlDateFormatter;

class DateTimeHelper
{
    public static function getMonthName(\DateTimeInterface $dateTime, string $locale): string
    {
        $formatter = new IntlDateFormatter($locale, 0, 0);
        $formatter->setPattern('MMMM');

        return $formatter->format($dateTime);

    }
}
