<?php

declare(strict_types=1);

namespace Arpegx\Bacup\Model;

class Item
{
    public function __construct(
        public readonly ?string $id = null,
        public readonly string $source
    ) {}
    public function find(string $id) {}
}
