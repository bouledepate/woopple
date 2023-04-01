<?php
/**
 * @var $this \yii\web\View
 * @var $stats array
 * @var $requests \yii\data\ActiveDataProvider
 */

use Woopple\Components\Enums\RestorePasswordStatus;

$statusList = RestorePasswordStatus::titles();
$this->title = 'Управление безопасностью'; ?>

<!-- Stats -->
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>1</h3>
                <p>Заявок на сброс пароля</p>
            </div>
            <div class="icon">
                <i class="ion">
                    <ion-icon name="clipboard-sharp"></ion-icon>
                </i>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-gradient-warning">
            <div class="inner">
                <h3>1</h3>
                <p>На рассмотрении</p>
            </div>
            <div class="icon">
                <i class="ion">
                    <ion-icon name="eye-sharp"></ion-icon>
                </i>
            </div>
            <!--            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-red">
            <div class="inner">
                <h3>1</h3>
                <p>Закрыто заявок на сброс пароля</p>
            </div>
            <div class="icon">
                <i class="ion">
                    <ion-icon name="checkmark-circle-sharp"></ion-icon>
                </i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <section class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Заявки на сброс пароля</h3>
            </div>
            <div class="card-body">
                <?= \yii\grid\GridView::widget([
                    'dataProvider' => $requests,
                    'columns' => [
                        [
                            'header' => 'Отправитель',
                            'value' => function ($data) {
                                return $data->user->login;
                            }
                        ],
                        'reason',
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'value' => function ($data) use ($statusList) {
                                return $statusList[$data->status];
                            }
                        ],
                        'request_date',
                        [
                            'header' => 'Рассмотрел',
                            'value' => function ($data) {
                                return $data?->moderator->login ?? '(Не рассмотрено)';
                            }
                        ]
                    ]
                ]) ?>
            </div>
        </div>
    </section>
</div>