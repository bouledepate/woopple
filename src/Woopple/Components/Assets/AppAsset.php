<?php

namespace Woopple\Components\Assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@public';
    public $css = [];
    public $js = [];
    public $depends = [
        'yii\web\YiiAsset',
        'Woopple\Components\Assets\BootstrapAsset',
        'hail812\adminlte3\assets\FontAwesomeAsset',
        'hail812\adminlte3\assets\AdminLteAsset'
    ];
}