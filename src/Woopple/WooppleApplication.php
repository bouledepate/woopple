<?php

namespace Woopple;

use Core\Component\Application;
use yii\web\Application as YiiApp;

class WooppleApplication extends Application
{
    protected function loadConfigurationFile(): void
    {
        $this->config = require dirname(__DIR__, 2) . '/config/woopple/main.php';
    }

    protected function buildYiiApplication(): void
    {
        $this->application = new YiiApp($this->config);
    }

    public function run(): int
    {
        return $this->application->run();
    }
}