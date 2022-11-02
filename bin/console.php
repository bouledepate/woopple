<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');


require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/vendor/yiisoft/yii2/Yii.php';

\Dotenv\Dotenv::createImmutable(__DIR__)->load();

$config = require  dirname(__DIR__) . '/config/Console/main.php';

$application = new yii\console\Application($config);
$exitCode = $application->run();
exit($exitCode);