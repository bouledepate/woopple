<?php

namespace Woopple;

use yii\web\Application as YiiApp;
use Core\Components\Application;
use Woopple\Modules\Admin\AdminModule;

class WooppleApplication extends Application
{
    protected function loadConfigurationFile(): void
    {
        $this->config = require dirname(__DIR__, 2) . '/app/woopple/config/main.php';
    }

    protected function buildYiiApplication(): void
    {
        $this->registerModuleDependencies();
        $this->application = new YiiApp($this->config);
    }

    public function run(): int
    {
        return $this->application->run();
    }

    public function registerModuleDependencies(): void
    {
        $this->config['modules'] = [
            'admin' => [
                'class' => AdminModule::class
            ]
        ];
    }
}