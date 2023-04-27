<?php

declare(strict_types=1);

namespace Woopple\Components\Rbac;

final class Role implements Item
{
    /**
     * @param string $key
     * @param string $title
     * @param string $description
     * @param Permission[] $permissions
     */
    public function __construct(
        public readonly string $key,
        public readonly string $description,
        public readonly array  $permissions
    )
    {
    }
}