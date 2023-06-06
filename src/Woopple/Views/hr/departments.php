<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use yii\bootstrap4\Html;

$this->title = 'Отделы компании'; ?>


<div class="row">
    <section class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <a href="<?= \yii\helpers\Url::to(['hr/add-department']) ?>" class="btn btn-success my-2">
                    Создать отдел
                </a>
            </div>
            <div class="card-body">
                <?= \yii\grid\GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        'name',
                        [
                            'attribute' => 'lead',
                            'format' => 'html',
                            'value' => function (\Woopple\Models\Structure\Department $data) {
                                return "<a href='/profile/{$data->departmentLead->login}'>{$data->departmentLead->profile->fullName()}</a>";
                            }
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{update}',
                            'buttons' => [
                                'update' => function ($url, $model) {
                                    return Yii::$app->user->can(\Core\Enums\Permission::MODIFY_DEPARTMENT->value) ?
                                        Html::a('<span class="fa fa-pen"></span>', $url, [
                                            'title' => 'Обновить',
                                        ]) : '';
                                }
                            ],
                            'urlCreator' => function ($action, $model, $key, $index) {
                                return match ($action) {
                                    'update' => \yii\helpers\Url::to(['hr/modify-department', 'id' => $model->id])
                                };
                            }
                        ]
                    ]
                ]) ?>
            </div>
        </div>
    </section>
</div>
