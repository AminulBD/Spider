<?php

namespace AminulBD\Spider\Contracts;

use AminulBD\Spider\Support\Collection;

interface Engine
{
    public function find(array $query = []): self;

    public function hasNext(): bool;

    public function next(): Collection;
}
