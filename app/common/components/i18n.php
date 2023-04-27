<?php

return [
    'sourcePath' => dirname(__DIR__, 4),
    'languages' => ['en-EN', 'ru-RU'],
    'translator' => 'Yii::t',
    'sort' => false,
    'removeUnused' => false,
    'only' => ['*.php'],
    'except' => [
        '.svn',
        '.git',
        '.gitignore',
        '.gitkeep',
        '.hgignore',
        '.hgkeep',
        '/messages',
        '/vendor',
    ],
    'format' => 'php',
    'messagePath' => dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'messages',
    'overwrite' => true,
];