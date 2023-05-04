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
            'tableOptions' => ['class' => 'table table-bordered'],
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
            'tableOptions' => ['class' => 'table table-bordered'],
            'columns' => [
                'title',
                'created_at',
                'expiration_date',
                [
                    'header' => 'Статус',
                    'format' => 'html',
                    'value' => function (Test $test) {
                        /** @var \Woopple\Models\User\User $user */
                        $user = Yii::$app->user->identity;
                        $result = $test->getResultByUser($user);

                        if (is_null($result)) {
                            return "<div class='badge badge-warning'>На проверке</div>";
                        } else {
                            return "<div class='badge badge-success'>Проверен</div>";
                        }
                    }
                ],
                [
                    'class' => \yii\grid\ActionColumn::class,
                    'template' => '{results}',
                    'buttons' => [
                        'results' => function ($url, Test $model) {
                            return Html::a('Ваши результаты', $url, [
                                'title' => 'Результаты',
                                'class' => 'btn btn-sm btn-success btn-block'
                            ]);
                        },
                    ],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        /** @var \Woopple\Models\User\User $user */
                        $user = Yii::$app->user->identity;
                        return match ($action) {
                            'results' => Url::to(['test/user-results', 'test' => $model->id, 'login' => $user->login])
                        };
                    }
                ]
            ]
        ]) ?>
    </div>
</div>
