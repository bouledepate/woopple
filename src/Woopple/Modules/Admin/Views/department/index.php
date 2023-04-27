<?php
/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

use yii\helpers\Html;

$this->title = 'Управление отделами';
?>

<div class="row">
    <section class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Информация об отделах компании</h3><br>
                <?php if (Yii::$app->user->can(\Core\Enums\Permission::CREATE_DEPARTMENT->value)): ?>
                    <a href="<?= \yii\helpers\Url::to(['department/add']) ?>" class="btn btn-success my-2">
                        Создать отдел
                    </a>
                <?php endif; ?>
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
                            'template' => '{update} {delete}',
                            'buttons' => [
                                'update' => function ($url, $model) {
                                    return Yii::$app->user->can(\Core\Enums\Permission::MODIFY_DEPARTMENT->value) ?
                                        Html::a('<span class="fa fa-pen"></span>', $url, [
                                            'title' => 'Обновить',
                                        ]) : '';
                                },
                                'delete' => function ($url, $model) {
                                    return Yii::$app->user->can(\Core\Enums\Permission::REMOVE_DEPARTMENT->value) ?
                                        Html::a('<span class="fa fa-trash"></span>', $url, [
                                            'title' => 'Удалить',
                                            'data' => [
                                                'confirm' => 'Вы уверены, что хотите удалить раздел?'
                                            ]
                                        ]) : '';
                                }
                            ],
                            'urlCreator' => function ($action, $model, $key, $index) {
                                return match ($action) {
                                    'update' => \yii\helpers\Url::to(['department/modify', 'id' => $model->id]),
                                    'delete' => \yii\helpers\Url::to(['department/remove', 'id' => $model->id])
                                };
                            }
                        ]
                    ]
                ]) ?>
            </div>
        </div>
    </section>
</div>
