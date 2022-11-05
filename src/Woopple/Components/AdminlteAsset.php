<?php

namespace Woopple\Components;

use yii\web\AssetBundle;

class AdminlteAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/dist';
    public $css = [
        'css/adminlte.min.css'
    ];
    public $js = [
        'js/adminlte.min.js'
    ];
}