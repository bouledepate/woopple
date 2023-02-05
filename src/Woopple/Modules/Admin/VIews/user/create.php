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
                        'showToggleAll' => false,
                        'pluginOptions' => ['multiple' => true],
                        'pluginEvents' => [
                            "select2:select" => new \yii\web\JsExpression("function(e) { roleInfo(e) }"),
                            "select2:unselect" => new \yii\web\JsExpression("function(e) { removeGroup(e) }")
                        ]
                    ]) ?>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title" id="asd">Описание выбранных ролей</h3>
                </div>
                <div class="card-body overflow-auto" style="height: 300px">
                    <ul id="role-description-container" class="list-unstyled"></ul>
                </div>
            </div>
        </section>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script type="application/javascript">
        function addGroup(role) {
            let key = role.key;
            let perms = role.permissions;
            $("#role-description-container").append(`<li id="role-${key}" class="list-unstyled">
                <span class="text-bold">${role.description}</span>
                <ul id="role-${key}-perms"></ul>
            </li>`)
            if (perms.length === 0) {
                $(`#role-${key}-perms`).append(`<li>Права отсутствуют</li>`)
            } else {
                perms.forEach(element => $(`#role-${key}-perms`).append(`<li>${element.description}</li>`))
            }
        }

        function removeGroup(event) {
            let role = "role-" + event.params.data.id
            $('#' + role).remove()
        }

        function roleInfo(event) {
            console.log(event)
            let key = event.params.data.id
            $.ajax({
                url: '/admin/json/role-info?key=' + key,
                success: function (data) {
                    console.log(data)
                    addGroup(data)
                }
            })
        }
    </script>
<?php ActiveForm::end() ?>