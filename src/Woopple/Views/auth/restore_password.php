<?php
/**
 * @var $this \yii\web\View;
 * @var $form \Woopple\Forms\LoginForm
 */

use yii\bootstrap4\ActiveForm;

$this->title = Yii::t('auth', 'login_page');
?>

<div class="card">
    <div class="card-header text-center card-outline card-primary">
        <span class="h1 login-brand">Woopple</span>
    </div>
    <div class="card-body login-card-body">
        <p class="login-box-msg">Запрос на сброс пароля был одобрен. Пожалуйста, укажите новый пароль.</p>
        <?php $loginForm = ActiveForm::begin([]) ?>
        <?= $loginForm->field($form, 'password')->passwordInput() ?>
        <?= $loginForm->field($form, 'repeatPassword')->passwordInput() ?>
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">Сменить пароль</button>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>

</div>
