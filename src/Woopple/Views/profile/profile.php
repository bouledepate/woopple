<?php
/**
 * @var \yii\web\View $this
 * @var \Woopple\Models\User\User $user
 * @var \Woopple\Models\Event\Event[] $events
 */

/**
 * @var \Woopple\Models\User\User $identity
 * @var \Woopple\Forms\ProfileForm $model
 */

use aryelds\sweetalert\SweetAlert;

$identity = Yii::$app->user->getIdentity();
$this->title = $user->login === $identity->login ? 'Ваш профиль' : 'Профиль сотрудника';
?>

<div class="row">
    <div class="col-md-3">

        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle" src="/assets/1fae73d4/img/user2-160x160.jpg"
                         alt="User profile picture">
                </div>
                <h3 class="profile-username text-center"><?= $user->profile->shortlyName() ?></h3>
                <p class="text-muted text-center"><?= $user->profile->position ?></p>
                <ul class="list-group list-group-unbordered mb-3">
                    <?php ?>
                    <li class="list-group-item">
                        <b>Отдел</b> <a class="float-right">1,322</a>
                    </li>
                    <li class="list-group-item">
                        <b>Команда/Подотдел</b> <a class="float-right">543</a>
                    </li>
                    <li class="list-group-item">
                        <b>Team Lead</b> <a class="float-right">13,287</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Информация обо мне</h3>
            </div>
            <div class="card-body">
                <?php if (empty($user->profile->education) && empty($user->profile->skills) && empty($user->profile->notes)): ?>
                    <p class="text-muted">Ничего не найдено</p>
                <?php else: ?>
                    <?php if (!empty($user->profile->education)): ?>
                        <strong><i class="fas fa-book mr-1"></i> Образование</strong>
                        <p class="text-muted"><?= $user->profile->education ?></p>
                    <?php endif; ?>
                    <?php if (!empty($user->profile->skills)): ?>
                        <strong><i class="fas fa-pencil-alt mr-1"></i> Навыки</strong>
                        <p class="text-muted"><?= $user->profile->skills ?></p>
                    <?php endif; ?>
                    <?php if (!empty($user->profile->notes)): ?>
                        <strong><i class="far fa-file-alt mr-1"></i> Заметки</strong>
                        <p class="text-muted"><?= $user->profile->notes ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <div class="col-md-9">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <!--                    <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Activity</a></li>-->
                    <li class="nav-item"><a class="nav-link active" href="#timeline" data-toggle="tab">Лента событий</a>
                    </li>
                    <?php if (Yii::$app->user->id === $user->id): ?>
                        <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Настройки</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="timeline">
                        <?= \Woopple\Components\Widgets\Timeline::widget([
                            'events' => $events,
                        ]) ?>
                    </div>
                    <?php if (Yii::$app->user->id === $user->id): ?>
                        <div class="tab-pane" id="settings">
                            <?php $form = \yii\bootstrap4\ActiveForm::begin([
                                'action' => \yii\helpers\Url::to(['profile/update']),
                                'enableClientValidation' => false
                            ]) ?>
                            <?= $form->field($model, 'education')->textarea(['value' => $user->profile->education]) ?>
                            <?= $form->field($model, 'skills')->textarea(['value' => $user->profile->skills]) ?>
                            <?= $form->field($model, 'notes')->textarea(['value' => $user->profile->notes]) ?>
                            <?= $form->field($model, 'profile')->hiddenInput(['value' => $user->profile->id])->label(false) ?>
                            <?= \yii\bootstrap4\Html::submitButton('Обновить', ['class' => 'btn btn-info']) ?>
                            <?php \yii\bootstrap4\ActiveForm::end() ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>