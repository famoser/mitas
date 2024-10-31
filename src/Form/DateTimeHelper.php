<?php

namespace App\Form;

use IntlDateFormatter;

class DateTimeHelper
{
    public static function getMonthName(\DateTimeInterface $dateTime, $locale): string
    {
        $formatter = new IntlDateFormatter($locale);
        $formatter->setPattern('MMMM');

        return $formatter->format($dateTime);

    }
}
