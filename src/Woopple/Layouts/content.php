<?php
/* @var $content string */

use aryelds\sweetalert\SweetAlert;
use yii\bootstrap4\Breadcrumbs;

?>
<?php if (Yii::$app->session->hasFlash('notifications')): ?>
    <?php foreach (Yii::$app->session->getFlash('notifications') as $flash): ?>
        <?= SweetAlert::widget([
            'options' => [
                'title' => $flash['title'],
                'text' => $flash['message'],
                'type' => $flash['type']
            ]
        ]); ?>
    <?php endforeach; ?>
<?php endif; ?>

<div class="content-wrapper px-4 py-2">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <?= !is_null($this->title)
                            ? \yii\helpers\Html::encode($this->title)
                            : \yii\helpers\Inflector::camelize($this->context->id) ?>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <?= Breadcrumbs::widget([
                        'links' => $this->params['breadcrumbs'] ?? [],
                        'options' => [
                            'class' => 'breadcrumb float-sm-right'
                        ]
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
    <section class="content px-2">
        <div class="container-fluid">
            <?= $content ?>
        </div>
    </section>
</div>