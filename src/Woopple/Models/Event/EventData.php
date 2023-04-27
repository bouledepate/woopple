<?php

namespace Woopple\Models\Event;

final class EventData
{
    public function __construct(
        public int     $user,
        public string  $title,
        public ?string $message,
        public Icon    $icon,
        /** @var Button[] $buttons */
        public ?array  $buttons = []
    )
    {
    }
}