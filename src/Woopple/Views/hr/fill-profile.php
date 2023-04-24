<?php
/**
 * @var \yii\web\View $this
 * @var \Woopple\Forms\Hr\FillProfileForm $model
 */

use yii\web\JsExpression;

$this->title = 'Заполнение профиля сотрудника';
?>

<div class="card">
    <div class="card-header">Личные данные сотрудника</div>
    <div class="card-body">
        <div class="col-md-6">
            <?php $form = \yii\bootstrap4\ActiveForm::begin() ?>
            <?= $form->field($model, 'firstName') ?>
            <?= $form->field($model, 'secondName') ?>
            <?= $form->field($model, 'lastName') ?>
            <?= $form->field($model, 'birthday')->widget(\kartik\date\DatePicker::class, [
                'options' => ['placeholder' => 'Укажите дату рождения']
            ]) ?>
            <?= $form->field($model, 'education')->textarea() ?>
            <?= $form->field($model, 'skills')->textarea() ?>
            <?= $form->field($model, 'position') ?>
            <?= $form->field($model, 'department')->dropdownList($model->departments, [
                'id' => 'dep-id',
                'prompt' => 'Выберите отдел'
            ]) ?>
            <?= $form->field($model, 'team')->widget(\kartik\depdrop\DepDrop::class, [
                'options' => ['id' => 'team-id'],
                'pluginOptions' => [
                    'depends' => ['dep-id'],
                    'placeholder' => 'Выберите команду',
                    'url' => \yii\helpers\Url::to(['json/teams'])
                ]
            ]) ?>

            <?= \yii\helpers\Html::submitButton('Заполнить', ['class' => 'btn btn-success']) ?>

            <?php \yii\bootstrap4\ActiveForm::end() ?>
        </div>
    </div>
</div>
