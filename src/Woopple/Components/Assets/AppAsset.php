<?php

namespace Woopple\Components\Assets;

use kartik\select2\Select2Asset;
use kartik\select2\Select2KrajeeAsset;
use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@public';
    public $css = [
        '/themes/v1/build/css/style.min.css'
    ];
    public $js = [
        '/themes/v1/build/js/tests.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'hail812\adminlte3\assets\FontAwesomeAsset',
        'hail812\adminlte3\assets\AdminLteAsset',
        Select2Asset::class,
        Select2KrajeeAsset::class
    ];
}