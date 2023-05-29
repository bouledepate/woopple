<?php
/**
 * @var $this \yii\web\View
 */

use Woopple\Components\Enums\AccountStatus;

$this->title = 'Управление доступом к сайту';
$statusList = AccountStatus::titles();
?>

<div class="row">
    <section class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Информация о пользователях</h3>
            </div>
            <div class="card-body">
                <?= \yii\grid\GridView::widget([
                    'dataProvider' => $users,
                    'columns' => [
                        'login',
                        'email',
                        'status' => [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'value' => function ($data) use ($statusList) {
                                return $statusList[$data->status];
                            }],
                        'last_seen',
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{block}',
                            'buttons' => [
                                'block' => function ($url, $model) {
                                    if (in_array($model->status, [
                                        AccountStatus::ACTIVE->value,
                                        AccountStatus::CREATED->value
                                    ])) {
                                        $link = '/admin/blacklist/add';
                                    } else {
                                        $link = '/admin/blacklist/remove';
                                    }

                                    return \yii\helpers\Html::a(
                                        text: in_array($model->status, [
                                            AccountStatus::ACTIVE->value,
                                            AccountStatus::CREATED->value
                                        ]) ? 'Заблокировать' : 'Разблокировать',
                                        url: \yii\helpers\Url::to([$link, 'login' => $model->login]),
                                        options: ['class' => in_array($model->status, [
                                            AccountStatus::ACTIVE->value,
                                            AccountStatus::CREATED->value
                                        ])
                                            ? 'btn btn-sm btn-danger'
                                            : 'btn btn-sm btn-secondary'
                                        ]
                                    );
                                }
                            ]
                        ]
                    ]
                ]) ?>
            </div>
        </div>
    </section>
</div>
