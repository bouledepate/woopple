<?php

declare(strict_types=1);

namespace Woopple\Components\Rbac;

final class Permission implements Item
{
    public function __construct(
        public string $key,
        public string $description
    )
    {
    }
}