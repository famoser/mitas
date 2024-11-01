<?php

declare(strict_types=1);

/*
 * This file is part of the evoting.uzh.ch project.
 *
 * (c) Florian Moser <git@famoser.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TwigExtension extends AbstractExtension
{
    /**
     * makes the filters available to twig.
     *
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('format_date', [$this, 'formatDateFilter']),
            new TwigFilter('format_date_time', [$this, 'formatDateTimeFilter']),
        ];
    }

    public function formatDateFilter(?\DateTimeInterface $date): string
    {
        if ($date instanceof \DateTimeInterface) {
            return $date->format('d.m.Y');
        }

        return '-';
    }

    public function formatDateTimeFilter(?\DateTimeInterface $date): string
    {
        if ($date instanceof \DateTimeInterface) {
            return $date->format('d.m.Y H:i');
        }

        return '-';
    }
}
