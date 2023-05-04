<?php
/**
 * @var \yii\web\View $this
 * @var \Woopple\Models\User\User[] $respondents
 * @var \Woopple\Models\Test\Test $test
 */

use yii\helpers\Url;

$this->title = 'Респонденты'; ?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <a href="<?= Url::to(['test/control']) ?>" class="btn btn-secondary btn-sm">Вернуться</a>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Имя пользователя</th>
                <th scope="col">Статус прохождения теста</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($respondents as $key => $user): ?>
                <tr>
                    <td><?= $user->profile->fullName() ?></td>
                    <td>
                        <?php if ($test->isAlreadyPassedBy($user)): ?>
                            <span class="badge bg-success">Прошел тест</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark">Не прошел тест</span>
                        <?php endif ?>
                    </td>
                    <td>
                        <?php if ($test->isAlreadyPassedBy($user)): ?>
                            <?php if (is_null($test->getResultByUser($user))): ?>
                                <a href="<?= Url::to(['test/review', 'test' => $test->id, 'user' => $user->id]) ?>"
                                   class="btn btn-primary btn-sm">Проверить</a>
                            <?php else: ?>
                                <a href="<?= Url::to(['test/user-results', 'test' => $test->id, 'login' => $user->login]) ?>"
                                   class="btn btn-success btn-sm">Результаты</a>
                            <?php endif; ?>
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
