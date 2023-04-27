<?php

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use Woopple\Models\Test\TestState;

$this->title = 'Раздел тестирования'; ?>


<div class="row">
    <div class="col-md-3">
        <div class="card card-success card-outline">
            <div class="card-header">
                <span class="h4">Панель управления</span>
            </div>
            <div class="card-body">
                <ul class="list-group mb-3">
                    <li class="list-group-item text-justify">
                        Инструмент тестирования отличное решение для сбора информации о сотрудниках: их стремления,
                        уровень знаний и заинтересованности. Чтобы создать тест, нажмите на кнопку ниже.
                        <a href="<?= \yii\helpers\Url::to(['test/create-test']) ?>" class="btn btn-info btn-block mt-3">Создать
                            новый тест</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card card-primary">
            <div class="card-header">
                <span class="card-title">
                    Ваши созданные тесты
                </span>
            </div>
            <div class="card-body">
                <?= \yii\grid\GridView::widget([
                    'dataProvider' => $dataProvider,
                    'tableOptions' => ['class' => 'table table-bordered'],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'header' => 'Наименование теста',
                            'format' => 'html',
                            'value' => function (\Woopple\Models\Test\Test $data) {
                                return $data->title . "<br><small>{$data->created_at}</small>";
                            }
                        ],
                        [
                            'attribute' => 'availability',
                            'value' => function (\Woopple\Models\Test\Test $data) {
                                return \Woopple\Models\Test\TestAvailability::tryFrom($data->availability)->title();
                            }
                        ],
                        [
                            'attribute' => 'state',
                            'format' => 'html',
                            'contentOptions' => ['class' => 'project-state'],
                            'value' => function (\Woopple\Models\Test\Test $data) {
                                $state = \Woopple\Models\Test\TestState::tryFrom($data->state);
                                return match ($state) {
                                    TestState::PROCESS => "<span class='badge badge-warning'>{$state->title()}</span>",
                                    TestState::PASSED => "<span class='badge badge-success'>{$state->title()}</span>",
                                    TestState::EXPIRED => "<span class='badge badge-secondary'>{$state->title()}</span>",
                                    TestState::CANCELED => "<span class='badge badge-danger'>{$state->title()}</span>",
                                };
                            }
                        ],
                        [
                            'header' => 'Прогресс',
                            'format' => 'html',
                            'value' => function (\Woopple\Models\Test\Test $data) {
                                return $data->currentProgress();
                            }
                        ]
                    ]
                ]) ?>
            </div>
        </div>
    </div>
</div>