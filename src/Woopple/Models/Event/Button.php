<?php

namespace Woopple\Models\Event;

final class Button
{
    public function __construct(
        public string $title,
        public string $link,
        public string $style
    )
    {
    }
}