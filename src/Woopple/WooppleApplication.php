<?php

namespace Woopple;

use Core\Components\Application;
use Core\Enums\Environment;
use yii\web\Application as YiiApp;

class WooppleApplication extends Application
{
    protected function loadConfigurationFile(): void
    {
        $this->config = require dirname(__DIR__, 2) . '/app/woopple/config/main.php';
    }

    protected function buildYiiApplication(): void
    {
        $this->application = new YiiApp($this->config);
    }

    protected function buildApplication(): void
    {
    }

    public function run(): int
    {
        return $this->application->run();
    }
}