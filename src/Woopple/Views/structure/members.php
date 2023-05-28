<?php

use yii\bootstrap4\Modal;
use yii\helpers\Html;

/** @var \Woopple\Models\User\User $current */
$current = Yii::$app->user->identity;
$this->title = 'Сотрудники'; ?>


<?php Modal::begin([
    'id' => 'change-position-modal',
    'title' => 'Изменение должности сотрудника',
    'footer' => \yii\helpers\Html::button('Подтвердить', [
        'id' => 'position-submit-btn',
        'class' => 'btn btn-success',
        'onclick' => 'changePosition()'
    ])
]) ?>
<div class="form-group">
    <label>Должность сотрудника</label>
    <input type="text" name="position" id="position-field" class="form-control" placeholder="Введите новую должность">
</div>
<?= \yii\helpers\Html::hiddenInput('user_id', null, ['id' => 'user_id-field']) ?>
<?php Modal::end() ?>

<div class="card card-primary card-outline">
    <div class="card-body">
        <?= \yii\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-bordered'],
            'columns' => [
                [
                    'header' => 'ФИО',
                    'value' => function (\Woopple\Models\User\User $user) {
                        return Html::a($user->profile->fullName(), \yii\helpers\Url::to([
                            'profile/profile', 'login' => $user->login
                        ]));
                    },
                    'format' => 'html'
                ],
                [
                    'header' => 'Должность в компании',
                    'value' => function (\Woopple\Models\User\User $user) {
                        return $user->profile->position;
                    }
                ],
                [
                    'header' => 'Отдел',
                    'value' => function (\Woopple\Models\User\User $user) {
                        return $user->getDepartment()->name ?? "(Неизвестно)";
                    }
                ],
                [
                    'header' => 'Команда',
                    'value' => function (\Woopple\Models\User\User $user) {
                        return $user->getTeam()->name ?? "(Неизвестно)";
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{position}',
                    'buttons' => [
                        'position' => function ($url, \Woopple\Models\User\User $model) {
                            return Html::button('Сменить должность', [
                                'title' => 'Обновить',
                                'data-toggle' => 'modal',
                                'data-target' => '#change-position-modal',
                                'class' => 'btn btn-sm btn-secondary btn-block',
                                'data-user' => $model->id,
                                'onclick' => 'setUser(this)'
                            ]);
                        },
                    ],
                    'visibleButtons' => [
                        'position' => function (\Woopple\Models\User\User $model) use ($current) {
                            if (in_array(\Core\Enums\Role::HR->value, $current->roles->getValue())) {
                                return true;
                            }

                            if ($current->isDepartmentLead() && $current->getDepartment()->id == $model->getDepartment()?->id) {
                                return true;
                            }

                            if ($current->isTeamLead() && $current->getTeam()->id == $model->getTeam()?->id) {
                                return true;
                            }

                            return false;
                        }
                    ]
                ]
            ]
        ]) ?>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script type="application/javascript">
    function changePosition() {
        let position = document.getElementById('position-field').value
        let user = document.getElementById('user_id-field').value

        $.ajax({
            type: 'POST',
            url: "/structure/change-employee-position/" + user,
            data: {position: position}
        })
    }

    function setUser(object) {
        let id = object.dataset.user;
        $("#user_id-field").val(id)
    }
</script>