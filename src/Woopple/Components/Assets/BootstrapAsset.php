<?php

namespace Woopple\Components\Assets;

use yii\web\AssetBundle;

class BootstrapAsset extends AssetBundle
{
    public $sourcePath = '@npm/bootstrap/dist';
    public $js = [
        'js/bootstrap.bundle.min.js',
//        'js/bootstrap.min.js',
    ];
    public $css = [
        'css/bootstrap.min.css',
    ];
}