<?php
/**
 * @var \yii\data\ActiveDataProvider $notFinishedDataProvider
 * @var \yii\data\ActiveDataProvider $finishedDataProvider
 * @var \yii\web\View $this
 */

use Woopple\Models\Test\TestAvailability;
use Woopple\Models\Test\Test;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Список доступных тестов'; ?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <span class="card-title">Новые тесты</span>
    </div>
    <div class="card-body">
        <?= \yii\grid\GridView::widget([
            'dataProvider' => $notFinishedDataProvider,
            'columns' => [
                'title',
                'created_at',
                'expiration_date',
                [
                    'class' => \yii\grid\ActionColumn::class,
                    'template' => '{pass}',
                    'buttons' => [
                        'pass' => function ($url, Test $model) {
                            return Html::a('Пройти тестирование', $url, [
                                'title' => 'Пройти тест',
                                'class' => 'btn btn-sm btn-info btn-block'
                            ]);
                        },
                    ],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        return match ($action) {
                            'pass' => Url::to(['test/start-test', 'id' => $model->id])
                        };
                    }
                ]
            ]
        ]) ?>
    </div>
</div>


<div class="card card-outline card-success">
    <div class="card-header">
        <span class="card-title">Пройденные тесты</span>
    </div>
    <div class="card-body">
        <?= \yii\grid\GridView::widget([
            'dataProvider' => $finishedDataProvider,
            'columns' => [
                'title',
                'created_at',
                'expiration_date'
            ]
        ]) ?>
    </div>
</div>
