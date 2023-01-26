<?php
/* @var $content string */

use yii\bootstrap4\Breadcrumbs;

?>
<div class="content-wrapper px-4 py-2">
    <section class="content-header">
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
    </section>
    <section class="content px-2">
        <?= $content ?>
    </section>
</div>