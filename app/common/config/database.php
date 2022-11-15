<?php

$dsn = sprintf('%s:host=%s;port=%s;dbname=%s',
    $_ENV['DB_DRIVER'],
    $_ENV['DB_HOST'],
    $_ENV['DB_PORT'],
    $_ENV['DB_NAME']
);

return [
    'class' => \yii\db\Connection::class,
    'dsn' => $dsn,
    'username' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASS'],
    'charset' => 'utf8'
];