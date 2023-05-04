<?php

namespace Core\Components;

use Dotenv\Dotenv;
use Core\Enums\Environment;
use yii\base\Application as YiiApp;

abstract class Application
{
    protected array $config = [];
    protected YiiApp $application;

    public function __construct()
    {
        $this->loadEnvironmentVariables();
        $this->loadConfigurationFile();
        $this->buildApplication();
        $this->buildYiiApplication();
    }

    /**
     * Loading variables from .env file.
     * @return void
     */
    protected function loadEnvironmentVariables(): void
    {
        Dotenv::createImmutable(dirname(__DIR__, 3))->load();
    }

    /**
     * Build current application. Must contain calls of config methods before yii app building.
     * @return void
     */
    protected function buildApplication(): void
    {
    }

    /**
     * Returns requiring current application configuration file.
     * @return void
     */
    abstract protected function loadConfigurationFile(): void;

    abstract protected function buildYiiApplication(): void;

    abstract public function run(): int;
}