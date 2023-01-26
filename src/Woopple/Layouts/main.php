<?php

/* @var $this \yii\web\View */

/* @var $content string */

/** @var string $layout */

use yii\helpers\Html;
use Core\Enums\Environment;

\Woopple\Components\Assets\AppAsset::register($this);
$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback');
$assetDir = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
$publishedRes = Yii::$app->assetManager->publish('@vendor/hail812/yii2-adminlte3/src/web/js');
$layout = Yii::$app->session->get('layoutKey');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition sidebar-mini sidebar-collapse">
<?php $this->beginBody() ?>

<div class="wrapper">
    <?= \Woopple\Components\Widgets\Navbar::widget() ?>

    <?= $this->render('sidebar', ['assetDir' => $assetDir, 'layout' => $layout]) ?>

    <?= $this->render('content', ['content' => $content, 'assetDir' => $assetDir]) ?>

    <?= $this->render('footer') ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
