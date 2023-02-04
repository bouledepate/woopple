<?php
/**
 * @var $this \yii\web\View;
 * @var $form \Woopple\Forms\LoginForm
 */

use yii\bootstrap4\ActiveForm;

$this->title = Yii::t('auth', 'login_page');
?>

<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">Войдите в систему, чтобы начать сеанс</p>
        <?php $loginForm = ActiveForm::begin([]) ?>
        <form action="../../index3.html" method="post">
            <?= $loginForm->field($form, 'login', [
                'template' => '{beginLabel}{labelTitle}{endLabel}
                    <div class="input-group mb-3">
                        {input}
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        {hint}{error}
                    </div>']) ?>
            <?= $loginForm->field($form, 'password', [
                'template' => '{beginLabel}{labelTitle}{endLabel}
                    <div class="input-group mb-3">
                        {input}
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        {hint}{error}
                    </div>'])->passwordInput() ?>
            <div class="row">
                <div class="col-8">
                    <?= \yii\helpers\Html::activeCheckbox($form, 'remember', ['class' => 'icheck-primary']) ?>
                </div>
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">Войти</button>
                </div>
            </div>
        </form>
        <?php ActiveForm::end() ?>
        <p class="mb-1">
            <a href="forgot-password.html">Я забыл свой пароль</a>
        </p>
    </div>

</div>
