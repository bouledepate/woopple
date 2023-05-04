<?php
/**
 * @var \Woopple\Models\User\User $user
 */

$profile = $user->profile;
$department = $user->getDepartment();
$team = $user->getTeam();
?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h5 class="card-title">Информация о респонденте</h5>
    </div>
    <div class="card-body">
        <p class="card-text"><strong>Почта:</strong> <span class="float-right"><?= $user->email; ?></span></p>
        <p class="card-text"><strong>Должность:</strong> <span class="float-right"> <?= $profile->position; ?></span>
        </p>
        <p class="card-text">
            <strong>ФИО:</strong>
            <span class="float-right">
                 <a href="<?= \yii\helpers\Url::to(['profile/profile', 'login' => $user->login]) ?>">
                    <?= $profile->fullname(); ?>
                </a>
            </span>
        </p>
        <?php if ($department !== null): ?>
            <p class="card-text"><strong>Отдел:</strong> <span class="float-right"><?= $department->name; ?></span></p>
        <?php endif; ?>
        <?php if ($team !== null): ?>
            <p class="card-text"><strong>Команда:</strong> <span class="float-right"><?= $team->name; ?></span></p>
        <?php endif; ?>
    </div>
</div>
