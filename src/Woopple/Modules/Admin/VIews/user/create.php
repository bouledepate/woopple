<?php
/**
 * @var $this \yii\web\View
 * @var $roles Role[]
 * @var $form \Woopple\Modules\Admin\Forms\CreateUserForm;
 */

use yii\bootstrap4\ActiveForm;
use kartik\select2\Select2;
use Woopple\Components\Rbac\Role;

$this->title = Yii::t('admin/users', 'create_user_title');
$selectWidgetData = array_combine(
    array_map(fn(Role $role) => $role->key, $roles),
    array_map(fn(Role $role) => $role->description, $roles),
);
?>
<?php $activeForm = ActiveForm::begin([

]) ?>
    <div class="row">
        <section class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Базовая информация об аккаунте</h3>
                </div>
                <div class="card-body">
                    <?= $activeForm->field($form, 'login') ?>
                    <?= $activeForm->field($form, 'email') ?>
                </div>
            </div>
            <?= \yii\helpers\Html::a('Отменить', '', ['class' => 'btn btn-block btn-light']) ?>
            <?= \yii\helpers\Html::submitButton('Сохранить', ['class' => 'btn btn-block btn-success']) ?>
        </section>
        <section class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Безопасность и доступы</h3>
                </div>
                <div class="card-body">
                    <?= $activeForm->field($form, 'password')->passwordInput() ?>
                    <?= $activeForm->field($form, 'passwordRepeat')->passwordInput() ?>
                    <?= $activeForm->field($form, 'roles')->widget(Select2::class, [
                        'bsVersion' => '3',
                        'data' => $selectWidgetData,
                        'pluginOptions' => ['multiple' => true]
                    ]) ?>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Описание выбранных ролей</h3>
                </div>
                <div class="card-body">

                </div>
            </div>
        </section>
    </div>
<?php ActiveForm::end() ?>