<?php
/**
 * @var $this \yii\web\View;
 */


$this->title = Yii::t('auth', 'login_page');
?>

<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">Запрос на сброс пароля был отправлен.</p>
        <div class="row">
            <div class="col-12">
                <a href="/auth/login" class="btn btn-info btn-block">Вернуться</a>
            </div>
        </div>
    </div>
</div>
