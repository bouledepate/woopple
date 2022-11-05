<?php

namespace Woopple\Components;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@public';
    public $css = [];
    public $js = [];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'Woopple\Components\AdminlteAsset',
        'Woopple\Components\FontAwesomeAsset'
    ];
}