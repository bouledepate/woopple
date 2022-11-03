<?php

namespace Core\Component;

use Dotenv\Dotenv;
use Core\Enum\Environment;
use yii\base\Application as YiiApp;

abstract class Application
{
    protected ?array $config;
    protected YiiApp $application;

    public function __construct()
    {
        $this->loadEnvironmentVariables();
        $this->loadConfigurationFile();
        $this->defineYiiConstants();
        $this->buildYiiApplication();
    }

    protected function loadEnvironmentVariables(): void
    {
        Dotenv::createImmutable(dirname(__DIR__, 3))->load();
    }

    protected function defineYiiConstants(): void
    {
        if (Environment::current() === Environment::DEVELOPMENT) {
            defined('YII_DEBUG') or define('YII_DEBUG', true);
            defined('YII_ENV') or define('YII_ENV', Environment::DEVELOPMENT->value);
        }
    }

    abstract protected function loadConfigurationFile(): void;

    abstract protected function buildYiiApplication(): void;

    abstract public function run(): int;
}