<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use Woopple\Models\User\User;
use Woopple\Components\Rbac\Rbac;
use Woopple\Components\Rbac\Role;


$this->title = 'Новички компании'; ?>

<div class="row">
    <section class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Информация об новичках</h3><br>
            </div>
            <div class="card-body">
                <?= \yii\grid\GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        [
                            'attribute' => 'login'
                        ],
                        [
                            'attribute' => 'email'
                        ],
                        [
                            'attribute' => 'created'
                        ],
                        [
                            'header' => 'Роли субъекта',
                            'attribute' => 'roles',
                            'value' => function (User $data) {
                                return implode(', ', array_map(function (Role $role) {
                                    return $role->description;
                                }, Rbac::parse($data->roles->getValue())));
                            }
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{fill}',
                            'buttons' => [
                                'fill' => function ($url, $model) {
                                    return "<a href='$url' class='btn btn-block btn-sm btn-outline-secondary'>Заполнить</a>";
                                }
                            ],
                            'urlCreator' => function ($action, $model, $key, $index) {
                                return match ($action) {
                                    'fill' => \yii\helpers\Url::to(['hr/fill-profile', 'id' => $model->id])
                                };
                            }
                        ]
                    ]
                ]) ?>
            </div>
        </div>
    </section>
</div>
