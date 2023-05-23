<?php
/**
 * @var $this \yii\web\View;
 */


$this->title = Yii::t('auth', 'login_page');
?>

<div class="card">
    <div class="card-header text-center card-outline card-danger">
        <span class="h1 login-brand">Woopple</span>
    </div>
    <div class="card-body login-card-body">
        <p class="login-box-msg">Запрос на сброс пароля был отклонён. За подробностями обращайтесь к TI/HR/вашему
            TeamLead.</p>
        <div class="row">
            <div class="col-12">
                <a href="/auth/login" class="btn btn-danger btn-block">Вернуться</a>
            </div>
        </div>
    </div>
</div>
