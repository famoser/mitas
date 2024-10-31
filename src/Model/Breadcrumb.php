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

namespace App\Model;

readonly class Breadcrumb
{
    public function __construct(private string $title, private ?string $path = null)
    {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }
}
