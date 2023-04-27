<?php

namespace Woopple\Models\Event;

final class Icon
{
    public function __construct(
        public string $icon,
        public string $background
    )
    {
    }
}