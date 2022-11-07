<?php

namespace Woopple\Components\Widgets;

use yii\base\Widget as YiiWidget;

abstract class Widget extends YiiWidget
{
    public function getViewPath(): string
    {
        $class = new \ReflectionClass($this);
        return dirname($class->getFileName()) . DIRECTORY_SEPARATOR . 'Views';
    }
}