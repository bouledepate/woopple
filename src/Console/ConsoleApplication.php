<?php

namespace Console;

use Core\Components\Application;
use yii\console\Application as YiiApp;

class ConsoleApplication extends Application
{
    protected function loadConfigurationFile(): void
    {
        $this->config = require dirname(__DIR__, 2) . '/app/console/main.php';
    }

    protected function buildYiiApplication(): void
    {
        $this->application = new YiiApp($this->config);
    }

    public function run(): int
    {
        return $this->application->run();
    }

    protected function buildApplication(): void
    {
    }
}