<?php
/**
 * @var $this \yii\web\View
 * @var $stats array
 * @var $users \yii\data\ActiveDataProvider
 */

use Woopple\Components\Enums\AccountStatus;

$statusList = AccountStatus::titles();
$this->title = Yii::t('site', 'admin-users-control'); ?>

<!-- Stats -->
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $stats['new'] ?></h3>
                <p><?= Yii::t('admin/users', 'stats_new_users') ?></p>
            </div>
            <div class="icon">
                <i class="ion">
                    <ion-icon name="person-add"></ion-icon>
                </i>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $stats['total'] ?></h3>
                <p><?= Yii::t('admin/users', 'stats_users') ?></p>
            </div>
            <div class="icon">
                <i class="ion">
                    <ion-icon name="people"></ion-icon>
                </i>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-gradient-danger">
            <div class="inner">
                <h3><?= $stats['blocked'] ?></h3>
                <p><?= Yii::t('admin/users', 'stats_blocked_users') ?></p>
            </div>
            <div class="icon">
                <i class="ion">
                    <ion-icon name="trash"></ion-icon>
                </i>
            </div>
            <!--            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
        </div>
    </div>
</div>

<div class="row">
    <section class="col-lg-9">
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
                        'created',
                        'updated',
                        'last_seen'
                    ]
                ]) ?>
            </div>
        </div>
    </section>
    <section class="col-lg-3">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Поиск пользователей</h3>
            </div>
            <div class="card-body">
                Hello world
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Панель инструментов</h3>
            </div>
            <div class="card-body">
                <a href="<?= \yii\helpers\Url::to('/admin/user/create') ?>"
                   class="btn btn-block bg-gradient-success btn-flat">
                    <i class="fas fa-user"></i>
                    <?= Yii::t('admin/users', 'add_user_btn') ?>
                </a>
                <a class="btn btn-block bg-gradient-info btn-flat">
                    <i class="fa fa-lock"></i>
                    <?= Yii::t('admin/users', 'security_management_btn') ?>
                </a>
                <a class="btn btn-block bg-gradient-info btn-flat">
                    <i class="fas fa-ban"></i>
                    <?= Yii::t('admin/users', 'block_list_btn') ?>
                </a>
            </div>
        </div>
    </section>
</div>