<?php
/**
 * @var \yii\web\View $this
 * @var \Woopple\Models\Structure\Department|null $department
 * @var \Woopple\Modules\Admin\Forms\DepartmentForm $model
 */

use kartik\select2\Select2;

$this->title = is_null($department) ? 'Создание отдела' : 'Редактирование отдела' ?>

<div class="row">
    <section class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Информация об отделе</h3><br>
            </div>
            <div class="card-body">
                <?php $form = \yii\bootstrap4\ActiveForm::begin() ?>
                <?= $form->field($model, 'name')->textInput() ?>
                <?= $form->field($model, 'lead')->widget(Select2::class, [
                    'data' => $model->users,
                    'options' => ['placeholder' => 'Выберите сотрудника ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
                <?= $form->field($model, 'leadPosition') ?>
                <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
                <?= \yii\helpers\Html::submitButton(is_null($department)
                    ? 'Создать отдел'
                    : 'Изменить отдел',
                    ['class' => 'btn btn-success']) ?>
                <?php \yii\bootstrap4\ActiveForm::end() ?>
            </div>
        </div>
    </section>
</div>