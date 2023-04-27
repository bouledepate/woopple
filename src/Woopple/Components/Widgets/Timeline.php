<?php

namespace Woopple\Components\Widgets;

use Woopple\Models\Event\Event;

class Timeline extends Widget
{
    public array $events;

    public function init()
    {
        parent::init();
        $this->group();
    }

    public function run()
    {
        return $this->render('timeline', ['events' => $this->events]);
    }

    protected function group(): void
    {
        $result = [];

        /** @var Event $event */
        foreach ($this->events as $event) {
            $result[$event->date->format('d M. Y')][] = $event;
        }

        $this->events = $result;

        uksort($this->events, function ($a, $b) {
            return strtotime($b) - strtotime($a);
        });
    }
}