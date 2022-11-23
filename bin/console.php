<?php

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/vendor/yiisoft/yii2/Yii.php';

$application = new \Console\ConsoleApplication();
$exitCode = $application->run();
exit($exitCode);