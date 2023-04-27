<?php

// todo: Рассмотреть возможность выноса объявления констант из данного файла. Предварительно реализовать
// todo: инициализацию приложения
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$application = new \Woopple\WooppleApplication();
$application->run();